<?php

class DB
{
    private $db;

    public function __construct($driver, $hostname, $username, $password, $database, $port = null)
    {
        $class = 'DB\\'.$driver;
        if (class_exists($class)) {
            $this->db = new $class($hostname, $username, $password, $database, $port);
        } else {
            exit('Error: Could not load database driver '.$driver.'!');
        }
    }

    // Private variables
    public $_protect_identifiers = true;
    public $_reserved_identifiers = ['*']; // Identifiers that should NOT be escaped
    public $ar_select = [];
    public $ar_distinct = false;
    public $ar_from = [];
    public $ar_join = [];
    public $ar_where = [];
    public $ar_like = [];
    public $ar_groupby = [];
    public $ar_having = [];
    public $ar_keys = [];
    public $ar_limit = false;
    public $ar_offset = false;
    public $ar_order = false;
    public $ar_orderby = [];
    public $ar_set = [];
    public $ar_wherein = [];
    public $ar_aliased_tables = [];
    public $ar_store_array = [];
    // Active Record Caching variables
    public $ar_caching = false;
    public $ar_cache_exists = [];
    public $ar_cache_select = [];
    public $ar_cache_from = [];
    public $ar_cache_join = [];
    public $ar_cache_where = [];
    public $ar_cache_like = [];
    public $ar_cache_groupby = [];
    public $ar_cache_having = [];
    public $ar_cache_orderby = [];
    public $ar_cache_set = [];

    public $ar_no_escape = [];
    public $ar_cache_no_escape = [];
    public $dbprefix = DB_PREFIX;
    public $swap_pre = '';
    public $_escape_char = '';
    public $conn_id = null;
    public $result_id = null;
    public $result_array = [];
    public $result_object = [];
    public $custom_result_object = [];
    public $current_row = 0;
    public $num_rows = 0;
    public $row_data = null;
    public $_random_keyword = null;
    public $queries = [];
    public $save_queries = true;

    /**
     * QB Cache WHERE data.
     *
     * @var array
     */
    protected $qb_cache_where = [];

    /**
     * QB WHERE group count.
     *
     * @var int
     */
    protected $qb_where_group_count = 0;

    /**
     * QB Caching flag.
     *
     * @var bool
     */
    protected $qb_caching = false;

    /**
     * QB WHERE group started flag.
     *
     * @var bool
     */
    protected $qb_where_group_started = false;

    /**
     * QB WHERE data.
     *
     * @var array
     */
    protected $qb_where = [];

    /**
     * ESCAPE character.
     *
     * @var string
     */
    protected $_like_escape_chr = '!';

    /**
     * ESCAPE statement string.
     *
     * @var string
     */
    protected $_like_escape_str = " ESCAPE '%s' ";
    // --------------------------------------------------------------------

    /**
     * Select.
     *
     * Generates the SELECT portion of the query
     *
     * @param	string
     *
     * @return object
     */
    public function select($select = '*', $escape = null)
    {
        if (is_string($select)) {
            $select = explode(',', $select);
        }
        foreach ($select as $val) {
            $val = trim($val);
            if ('' != $val) {
                $this->ar_select[] = $val;
                $this->ar_no_escape[] = $escape;
                if (true === $this->ar_caching) {
                    $this->ar_cache_select[] = $val;
                    $this->ar_cache_exists[] = 'select';
                    $this->ar_cache_no_escape[] = $escape;
                }
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Select Max.
     *
     * Generates a SELECT MAX(field) portion of a query
     *
     * @param	string	the field
     * @param	string	an alias
     *
     * @return object
     */
    public function select_max($select = '', $alias = '')
    {
        return $this->_max_min_avg_sum($select, $alias, 'MAX');
    }

    // --------------------------------------------------------------------

    /**
     * Select Min.
     *
     * Generates a SELECT MIN(field) portion of a query
     *
     * @param	string	the field
     * @param	string	an alias
     *
     * @return object
     */
    public function select_min($select = '', $alias = '')
    {
        return $this->_max_min_avg_sum($select, $alias, 'MIN');
    }

    // --------------------------------------------------------------------

    /**
     * Select Average.
     *
     * Generates a SELECT AVG(field) portion of a query
     *
     * @param	string	the field
     * @param	string	an alias
     *
     * @return object
     */
    public function select_avg($select = '', $alias = '')
    {
        return $this->_max_min_avg_sum($select, $alias, 'AVG');
    }

    // --------------------------------------------------------------------

    /**
     * Select Sum.
     *
     * Generates a SELECT SUM(field) portion of a query
     *
     * @param	string	the field
     * @param	string	an alias
     *
     * @return object
     */
    public function select_sum($select = '', $alias = '')
    {
        return $this->_max_min_avg_sum($select, $alias, 'SUM');
    }

    // --------------------------------------------------------------------

    /**
     * Processing Function for the four functions above:.
     *
     *	select_max()
     *	select_min()
     *	select_avg()
     *  select_sum()
     *
     * @param	string	the field
     * @param	string	an alias
     *
     * @return object
     */
    protected function _max_min_avg_sum($select = '', $alias = '', $type = 'MAX')
    {
        if (!is_string($select) or '' == $select) {
            $this->display_error('db_invalid_query');
        }
        $type = strtoupper($type);
        if (!in_array($type, ['MAX', 'MIN', 'AVG', 'SUM'])) {
            show_error('Invalid function type: '.$type);
        }
        if ('' == $alias) {
            $alias = $this->_create_alias_from_table(trim($select));
        }
        $sql = $type.'('.$this->_protect_identifiers(trim($select)).') AS '.$alias;
        $this->ar_select[] = $sql;
        if (true === $this->ar_caching) {
            $this->ar_cache_select[] = $sql;
            $this->ar_cache_exists[] = 'select';
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Determines the alias name based on the table.
     *
     * @param	string
     *
     * @return string
     */
    protected function _create_alias_from_table($item)
    {
        if (false !== strpos($item, '.')) {
            return end(explode('.', $item));
        }

        return $item;
    }

    // --------------------------------------------------------------------

    /**
     * DISTINCT.
     *
     * Sets a flag which tells the query string compiler to add DISTINCT
     *
     * @param	bool
     *
     * @return object
     */
    public function distinct($val = true)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : true;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * From.
     *
     * Generates the FROM portion of the query
     *
     * @param	mixed	can be a string or array
     *
     * @return object
     */
    public function from($from)
    {
        foreach ((array) $from as $val) {
            if (false !== strpos($val, ',')) {
                foreach (explode(',', $val) as $v) {
                    $v = trim($v);
                    $this->_track_aliases($v);
                    $this->ar_from[] = $this->_protect_identifiers($v, true, null, false);
                    if (true === $this->ar_caching) {
                        $this->ar_cache_from[] = $this->_protect_identifiers($v, true, null, false);
                        $this->ar_cache_exists[] = 'from';
                    }
                }
            } else {
                $val = trim($val);
                // Extract any aliases that might exist.  We use this information
                // in the _protect_identifiers to know whether to add a table prefix
                $this->_track_aliases($val);
                $this->ar_from[] = $this->_protect_identifiers($val, true, null, false);
                if (true === $this->ar_caching) {
                    $this->ar_cache_from[] = $this->_protect_identifiers($val, true, null, false);
                    $this->ar_cache_exists[] = 'from';
                }
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Join.
     *
     * Generates the JOIN portion of the query
     *
     * @param	string
     * @param	string	the join condition
     * @param	string	the type of join
     *
     * @return object
     */
    public function join($table, $cond, $type = '')
    {
        if ('' != $type) {
            $type = strtoupper(trim($type));
            if (!in_array($type, ['LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'])) {
                $type = '';
            } else {
                $type .= ' ';
            }
        }
        // Extract any aliases that might exist.  We use this information
        // in the _protect_identifiers to know whether to add a table prefix
        $this->_track_aliases($table);
        // Strip apart the condition and protect the identifiers
        if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match)) {
            $match[1] = $this->_protect_identifiers($match[1]);
            $match[3] = $this->_protect_identifiers($match[3]);
            $cond = $match[1].$match[2].$match[3];
        }
        // Assemble the JOIN statement
        $join = $type.'JOIN '.$this->_protect_identifiers($table, true, null, false).' ON '.$cond;
        $this->ar_join[] = $join;
        if (true === $this->ar_caching) {
            $this->ar_cache_join[] = $join;
            $this->ar_cache_exists[] = 'join';
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Where.
     *
     * Generates the WHERE portion of the query. Separates
     * multiple calls with AND
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function where($key, $value = null, $escape = true)
    {
        return $this->_where($key, $value, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR Where.
     *
     * Generates the WHERE portion of the query. Separates
     * multiple calls with OR
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function or_where($key, $value = null, $escape = true)
    {
        return $this->_where($key, $value, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * Where.
     *
     * Called by where() or or_where()
     *
     * @param	mixed
     * @param	mixed
     * @param	string
     *
     * @return object
     */
    protected function _where($key, $value = null, $type = 'AND ', $escape = null)
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        // If the escape value was not set will will base it on the global setting
        if (!is_bool($escape)) {
            $escape = $this->_protect_identifiers;
        }
        foreach ($key as $k => $v) {
            $prefix = (0 == count($this->ar_where) and 0 == count($this->ar_cache_where)) ? '' : $type;
            if (is_null($v) && !$this->_has_operator($k)) {
                // value appears not to have been set, assign the test to IS NULL
                $k .= ' IS NULL';
            }
            if (!is_null($v)) {
                if (true === $escape) {
                    $k = $this->_protect_identifiers($k, false, $escape);
                    $v = ' "'.$this->escape($v).'"';
                }

                if (!$this->_has_operator($k)) {
                    $k .= ' = ';
                }
            } else {
                $k = $this->_protect_identifiers($k, false, $escape);
            }
            $this->ar_where[] = $prefix.$k.$v;
            if (true === $this->ar_caching) {
                $this->ar_cache_where[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'where';
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Where_in.
     *
     * Generates a WHERE field IN ('item', 'item') SQL query joined with
     * AND if appropriate
     *
     * @param	string	The field to search
     * @param	array	The values searched on
     *
     * @return object
     */
    public function where_in($key = null, $values = null)
    {
        return $this->_where_in($key, $values);
    }

    // --------------------------------------------------------------------

    /**
     * Where_in_or.
     *
     * Generates a WHERE field IN ('item', 'item') SQL query joined with
     * OR if appropriate
     *
     * @param	string	The field to search
     * @param	array	The values searched on
     *
     * @return object
     */
    public function or_where_in($key = null, $values = null)
    {
        return $this->_where_in($key, $values, false, 'OR ');
    }

    // --------------------------------------------------------------------

    /**
     * Where_not_in.
     *
     * Generates a WHERE field NOT IN ('item', 'item') SQL query joined
     * with AND if appropriate
     *
     * @param	string	The field to search
     * @param	array	The values searched on
     *
     * @return object
     */
    public function where_not_in($key = null, $values = null)
    {
        return $this->_where_in($key, $values, true);
    }

    // --------------------------------------------------------------------

    /**
     * Where_not_in_or.
     *
     * Generates a WHERE field NOT IN ('item', 'item') SQL query joined
     * with OR if appropriate
     *
     * @param	string	The field to search
     * @param	array	The values searched on
     *
     * @return object
     */
    public function or_where_not_in($key = null, $values = null)
    {
        return $this->_where_in($key, $values, true, 'OR ');
    }

    // --------------------------------------------------------------------

    /**
     * Where_in.
     *
     * Called by where_in, where_in_or, where_not_in, where_not_in_or
     *
     * @param	string	The field to search
     * @param	array	The values searched on
     * @param	bool	If the statement would be IN or NOT IN
     * @param	string
     *
     * @return object
     */
    protected function _where_in($key = null, $values = null, $not = false, $type = 'AND ')
    {
        if (null === $key or null === $values) {
            return;
        }
        if (!is_array($values)) {
            $values = [$values];
        }
        $not = ($not) ? ' NOT' : '';
        foreach ($values as $value) {
            $this->ar_wherein[] = $this->escape($value);
        }
        $prefix = (0 == count($this->ar_where)) ? '' : $type;
        $where_in = $prefix.$this->_protect_identifiers($key).$not.' IN ('.implode(', ', $this->ar_wherein).') ';
        $this->ar_where[] = $where_in;
        if (true === $this->ar_caching) {
            $this->ar_cache_where[] = $where_in;
            $this->ar_cache_exists[] = 'where';
        }
        // reset the array for multiple calls
        $this->ar_wherein = [];

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Like.
     *
     * Generates a %LIKE% portion of the query. Separates
     * multiple calls with AND
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    // --------------------------------------------------------------------

    /**
     * Not Like.
     *
     * Generates a NOT LIKE portion of the query. Separates
     * multiple calls with AND
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }

    // --------------------------------------------------------------------

    /**
     * OR Like.
     *
     * Generates a %LIKE% portion of the query. Separates
     * multiple calls with OR
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function or_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    // --------------------------------------------------------------------

    /**
     * OR Not Like.
     *
     * Generates a NOT LIKE portion of the query. Separates
     * multiple calls with OR
     *
     * @param	mixed
     * @param	mixed
     *
     * @return object
     */
    public function or_not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
    }

    // --------------------------------------------------------------------

    /**
     * Like.
     *
     * Called by like() or orlike()
     *
     * @param	mixed
     * @param	mixed
     * @param	string
     *
     * @return object
     */
    protected function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
    {
        if (!is_array($field)) {
            $field = [$field => $match];
        }
        foreach ($field as $k => $v) {
            $k = $this->_protect_identifiers($k);
            $prefix = (0 == count($this->ar_like)) ? '' : $type;
            $v = $this->escape_like_str($v);

            if ('none' == $side) {
                $like_statement = $prefix." $k $not LIKE '{$v}'";
            } elseif ('before' == $side) {
                $like_statement = $prefix." $k $not LIKE '%{$v}'";
            } elseif ('after' == $side) {
                $like_statement = $prefix." $k $not LIKE '{$v}%'";
            } else {
                $like_statement = $prefix." $k $not LIKE '%{$v}%'";
            }
            // some platforms require an escape sequence definition for LIKE wildcards
            if ('' != $this->_like_escape_str) {
                $like_statement = $like_statement.sprintf($this->_like_escape_str, $this->_like_escape_chr);
            }
            $this->ar_like[] = $like_statement;
            if (true === $this->ar_caching) {
                $this->ar_cache_like[] = $like_statement;
                $this->ar_cache_exists[] = 'like';
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * GROUP BY.
     *
     * @param	string
     *
     * @return object
     */
    public function group_by($by)
    {
        if (is_string($by)) {
            $by = explode(',', $by);
        }
        foreach ($by as $val) {
            $val = trim($val);
            if ('' != $val) {
                $this->ar_groupby[] = $this->_protect_identifiers($val);
                if (true === $this->ar_caching) {
                    $this->ar_cache_groupby[] = $this->_protect_identifiers($val);
                    $this->ar_cache_exists[] = 'groupby';
                }
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Sets the HAVING value.
     *
     * Separates multiple calls with AND
     *
     * @param	string
     * @param	string
     *
     * @return object
     */
    public function having($key, $value = '', $escape = true)
    {
        return $this->_having($key, $value, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * Sets the OR HAVING value.
     *
     * Separates multiple calls with OR
     *
     * @param	string
     * @param	string
     *
     * @return object
     */
    public function or_having($key, $value = '', $escape = true)
    {
        return $this->_having($key, $value, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * Sets the HAVING values.
     *
     * Called by having() or or_having()
     *
     * @param	string
     * @param	string
     *
     * @return object
     */
    protected function _having($key, $value = '', $type = 'AND ', $escape = true)
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        foreach ($key as $k => $v) {
            $prefix = (0 == count($this->ar_having)) ? '' : $type;
            if (true === $escape) {
                $k = $this->_protect_identifiers($k);
            }
            if (!$this->_has_operator($k)) {
                $k .= ' = ';
            }
            if ('' != $v) {
                $v = ' '.$this->escape($v);
            }
            $this->ar_having[] = $prefix.$k.$v;
            if (true === $this->ar_caching) {
                $this->ar_cache_having[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'having';
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Sets the ORDER BY value.
     *
     * @param	string
     * @param	string	direction: asc or desc
     *
     * @return object
     */
    public function order_by($orderby, $direction = '')
    {
        if ('random' == strtolower($direction)) {
            $orderby = ''; // Random results want or don't need a field name
            $direction = $this->_random_keyword;
        } elseif ('' != trim($direction)) {
            $direction = (in_array(strtoupper(trim($direction)), ['ASC', 'DESC'], true)) ? ' '.$direction : ' ASC';
        }
        if (false !== strpos($orderby, ',')) {
            $temp = [];
            foreach (explode(',', $orderby) as $part) {
                $part = trim($part);
                if (!in_array($part, $this->ar_aliased_tables)) {
                    $part = $this->_protect_identifiers(trim($part));
                }
                $temp[] = $part;
            }
            $orderby = implode(', ', $temp);
        } elseif ($direction != $this->_random_keyword) {
            $orderby = $this->_protect_identifiers($orderby);
        }
        $orderby_statement = $orderby.$direction;
        $this->ar_orderby[] = $orderby_statement;
        if (true === $this->ar_caching) {
            $this->ar_cache_orderby[] = $orderby_statement;
            $this->ar_cache_exists[] = 'orderby';
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Sets the LIMIT value.
     *
     * @param	int	the limit value
     * @param	int	the offset value
     *
     * @return object
     */
    public function limit($value, $offset = '')
    {
        $this->ar_limit = (int) $value;
        if ('' != $offset) {
            $this->ar_offset = (int) $offset;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Sets the OFFSET value.
     *
     * @param	int	the offset value
     *
     * @return object
     */
    public function offset($offset)
    {
        $this->ar_offset = $offset;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * The "set" function.  Allows key/value pairs to be set for inserting or updating.
     *
     * @param	mixed
     * @param	string
     * @param	bool
     *
     * @return object
     */
    public function set($key, $value = '', $escape = true)
    {
        $key = $this->_object_to_array($key);
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        foreach ($key as $k => $v) {
            if (false === $escape) {
                $this->ar_set[$this->_protect_identifiers($k)] = $v;
            } else {
                $this->ar_set[$this->_protect_identifiers($k, false, true)] = $this->escape($v);
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Get.
     *
     * Compiles the select statement based on the other functions called
     * and runs the query
     *
     * @param	string	the table
     * @param	string	the limit clause
     * @param	string	the offset clause
     *
     * @return object
     */
    public function get($table = '', $limit = null, $offset = null)
    {
        if ('' != $table) {
            $this->_track_aliases($table);
            $this->from($table);
        }
        if (!is_null($limit)) {
            $this->limit($limit, $offset);
        }
        $sql = $this->_compile_select();
        $result = $this->query($sql);
        $this->_reset_select();

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Protect Identifiers.
     *
     * This function is used extensively by the Active Record class, and by
     * a couple functions in this class.
     * It takes a column or table name (optionally with an alias) and inserts
     * the table prefix onto it.  Some logic is necessary in order to deal with
     * column names that include the path.  Consider a query like this:
     *
     * SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
     *
     * Or a query with aliasing:
     *
     * SELECT m.member_id, m.member_name FROM members AS m
     *
     * Since the column name can include up to four segments (host, DB, table, column)
     * or also have an alias prefix, we need to do a bit of work to figure this out and
     * insert the table prefix (if it exists) in the proper position, and escape only
     * the correct identifiers.
     *
     * @param	string
     * @param	bool
     * @param	mixed
     * @param	bool
     *
     * @return string
     */
    public function _protect_identifiers($item, $prefix_single = false, $protect_identifiers = null, $field_exists = true)
    {
        if (!is_bool($protect_identifiers)) {
            $protect_identifiers = $this->_protect_identifiers;
        }
        if (is_array($item)) {
            $escaped_array = [];
            foreach ($item as $k => $v) {
                $escaped_array[$this->_protect_identifiers($k)] = $this->_protect_identifiers($v);
            }

            return $escaped_array;
        }
        // Convert tabs or multiple spaces into single spaces
        $item = preg_replace('/[\t ]+/', ' ', $item);
        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        if (false !== strpos($item, ' ')) {
            $alias = strstr($item, ' ');
            $item = substr($item, 0, -strlen($alias));
        } else {
            $alias = '';
        }
        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (false !== strpos($item, '(')) {
            return $item.$alias;
        }
        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (false !== strpos($item, '.')) {
            $parts = explode('.', $item);
            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables)) {
                if (true === $protect_identifiers) {
                    foreach ($parts as $key => $val) {
                        if (!in_array($val, $this->_reserved_identifiers)) {
                            $parts[$key] = $this->_escape_identifiers($val);
                        }
                    }
                    $item = implode('.', $parts);
                }

                return $item.$alias;
            }
            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ('' != $this->dbprefix) {
                // We now add the table prefix based on some logic.
                // Do we have 4 segments (hostname.database.table.column)?
                // If so, we add the table prefix to the column name in the 3rd segment.
                if (isset($parts[3])) {
                    $i = 2;
                }
                // Do we have 3 segments (database.table.column)?
                // If so, we add the table prefix to the column name in 2nd position
                elseif (isset($parts[2])) {
                    $i = 1;
                }
                // Do we have 2 segments (table.column)?
                // If so, we add the table prefix to the column name in 1st segment
                else {
                    $i = 0;
                }
                // This flag is set when the supplied $item does not contain a field name.
                // This can happen when this function is being called from a JOIN.
                if (false == $field_exists) {
                    ++$i;
                }
                // Verify table prefix and replace if necessary
                if ('' != $this->swap_pre && 0 === strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre))) {
                    $parts[$i] = preg_replace('/^'.$this->swap_pre."(\S+?)/", $this->dbprefix.'\\1', $parts[$i]);
                }
                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->dbprefix)) != $this->dbprefix) {
                    $parts[$i] = $this->dbprefix.$parts[$i];
                }
                // Put the parts back together
                $item = implode('.', $parts);
            }
            if (true === $protect_identifiers) {
                $item = $this->_escape_identifiers($item);
            }

            return $item.$alias;
        }
        // Is there a table prefix?  If not, no need to insert it
        if ('' != $this->dbprefix) {
            // Verify table prefix and replace if necessary
            if ('' != $this->swap_pre && 0 === strncmp($item, $this->swap_pre, strlen($this->swap_pre))) {
                $item = preg_replace('/^'.$this->swap_pre."(\S+?)/", $this->dbprefix.'\\1', $item);
            }
            // Do we prefix an item with no segments?
            if (true == $prefix_single and substr($item, 0, strlen($this->dbprefix)) != $this->dbprefix) {
                $item = $this->dbprefix.$item;
            }
        }
        if (true === $protect_identifiers and !in_array($item, $this->_reserved_identifiers)) {
            $item = $this->_escape_identifiers($item);
        }

        return $item.$alias;
    }

    /**
     * "Count All Results" query.
     *
     * Generates a platform-specific query string that counts all records
     * returned by an Active Record query.
     *
     * @param	string
     *
     * @return string
     */
    public function count_all_results($table = '')
    {
        if ('' != $table) {
            $this->_track_aliases($table);
            $this->from($table);
        }
        $sql = $this->_compile_select($this->_count_string.$this->_protect_identifiers('numrows'));
        $query = $this->query($sql);
        $this->_reset_select();
        if (0 == $query->num_rows()) {
            return 0;
        }
        $row = $query->row();

        return (int) $row->numrows;
    }

    // --------------------------------------------------------------------

    /**
     * Get_Where.
     *
     * Allows the where clause, limit and offset to be added directly
     *
     * @param	string	the where clause
     * @param	string	the limit clause
     * @param	string	the offset clause
     *
     * @return object
     */
    public function get_where($table = '', $where = null, $limit = null, $offset = null)
    {
        if ('' != $table) {
            $this->from($table);
        }
        if (!is_null($where)) {
            $this->where($where);
        }
        if (!is_null($limit)) {
            $this->limit($limit, $offset);
        }
        $sql = $this->_compile_select();
        $result = $this->query($sql);
        $this->_reset_select();

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Insert_Batch.
     *
     * Compiles batch insert strings and runs the queries
     *
     * @param	string	the table to retrieve the results from
     * @param	array	an associative array of insert values
     *
     * @return object
     */
    public function insert_batch($table = '', $set = null)
    {
        if (!is_null($set)) {
            $this->set_insert_batch($set);
        }
        if (0 == count($this->ar_set)) {
            if ($this->db_debug) {
                //No valid data array.  Folds in cases where keys and values did not match up
                return $this->display_error('db_must_use_set');
            }

            return false;
        }
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        }
        // Batch this baby
        for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100) {
            $sql = $this->_insert_batch($this->_protect_identifiers($table, true, null, false), $this->ar_keys, array_slice($this->ar_set, $i, 100));
            //echo $sql;
            $this->query($sql);
        }
        $this->_reset_write();

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * The "set_insert_batch" function.  Allows key/value pairs to be set for batch inserts.
     *
     * @param	mixed
     * @param	string
     * @param	bool
     *
     * @return object
     */
    public function set_insert_batch($key, $value = '', $escape = true)
    {
        $key = $this->_object_to_array_batch($key);
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        $keys = array_keys(current($key));
        sort($keys);
        foreach ($key as $row) {
            if (count(array_diff($keys, array_keys($row))) > 0 or count(array_diff(array_keys($row), $keys)) > 0) {
                // batch function above returns an error on an empty array
                $this->ar_set[] = [];

                return;
            }
            ksort($row); // puts $row in the same order as our keys
            if (false === $escape) {
                $this->ar_set[] = '('.implode(',', $row).')';
            } else {
                $clean = [];
                foreach ($row as $value) {
                    $clean[] = $this->escape($value);
                }
                $this->ar_set[] = '('.implode(',', $clean).')';
            }
        }
        foreach ($keys as $k) {
            $this->ar_keys[] = $this->_protect_identifiers($k);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Insert.
     *
     * Compiles an insert string and runs the query
     *
     * @param	string	the table to insert data into
     * @param	array	an associative array of insert values
     *
     * @return object
     */
    public function insert($table = '', $set = null, $escape = null)
    {
        if (!is_null($set)) {
            $this->set($set, '', $escape);
        }
        if (0 == count($this->ar_set)) {
            if ($this->db_debug) {
                return $this->display_error('db_must_use_set');
            }

            return false;
        }
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        }

        $sql = $this->_insert($this->_protect_identifiers($table, true, $escape, false), array_keys($this->ar_set), array_values($this->ar_set));
        $this->_reset_write();

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Replace.
     *
     * Compiles an replace into string and runs the query
     *
     * @param	string	the table to replace data into
     * @param	array	an associative array of insert values
     *
     * @return object
     */
    public function replace($table = '', $set = null)
    {
        if (!is_null($set)) {
            $this->set($set);
        }
        if (0 == count($this->ar_set)) {
            if ($this->db_debug) {
                return $this->display_error('db_must_use_set');
            }

            return false;
        }
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        }
        $sql = $this->_replace($this->_protect_identifiers($table, true, null, false), array_keys($this->ar_set), array_values($this->ar_set));
        $this->_reset_write();

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Update.
     *
     * Compiles an update string and runs the query
     *
     * @param	string	the table to retrieve the results from
     * @param	array	an associative array of update values
     * @param	mixed	the where clause
     *
     * @return object
     */
    public function update($table = '', $set = null, $where = null, $limit = null)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        if (!is_null($set)) {
            $this->set($set);
        }
        if (0 == count($this->ar_set)) {
            if ($this->db_debug) {
                return $this->display_error('db_must_use_set');
            }

            return false;
        }
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        }
        if (null != $where) {
            $this->where($where);
        }
        if (null != $limit) {
            $this->limit($limit);
        }
        $sql = $this->_update($this->_protect_identifiers($table, true, null, false), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
        $this->_reset_write();

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Update_Batch.
     *
     * Compiles an update string and runs the query
     *
     * @param	string	the table to retrieve the results from
     * @param	array	an associative array of update values
     * @param	string	the where key
     *
     * @return object
     */
    public function update_batch($table = '', $set = null, $index = null)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        if (is_null($index)) {
            if ($this->db_debug) {
                return $this->display_error('db_must_use_index');
            }

            return false;
        }
        if (!is_null($set)) {
            $this->set_update_batch($set, $index);
        }
        if (0 == count($this->ar_set)) {
            if ($this->db_debug) {
                return $this->display_error('db_must_use_set');
            }

            return false;
        }
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        }
        // Batch this baby
        for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100) {
            $sql = $this->_update_batch($this->_protect_identifiers($table, true, null, false), array_slice($this->ar_set, $i, 100), $this->_protect_identifiers($index), $this->ar_where);
            $this->query($sql);
        }
        $this->_reset_write();
    }

    // --------------------------------------------------------------------

    /**
     * The "set_update_batch" function.  Allows key/value pairs to be set for batch updating.
     *
     * @param	array
     * @param	string
     * @param	bool
     *
     * @return object
     */
    public function set_update_batch($key, $index = '', $escape = true)
    {
        $key = $this->_object_to_array_batch($key);
        if (!is_array($key)) {
            // @todo error
        }
        foreach ($key as $k => $v) {
            $index_set = false;
            $clean = [];
            foreach ($v as $k2 => $v2) {
                if ($k2 == $index) {
                    $index_set = true;
                } else {
                    $not[] = $k2.'-'.$v2;
                }
                if (false === $escape) {
                    $clean[$this->_protect_identifiers($k2)] = $v2;
                } else {
                    $clean[$this->_protect_identifiers($k2)] = $this->escape($v2);
                }
            }
            if (false == $index_set) {
                return $this->display_error('db_batch_missing_index');
            }
            $this->ar_set[] = $clean;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Empty Table.
     *
     * Compiles a delete string and runs "DELETE FROM table"
     *
     * @param	string	the table to empty
     *
     * @return object
     */
    public function empty_table($table = '')
    {
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        } else {
            $table = $this->_protect_identifiers($table, true, null, false);
        }
        $sql = $this->_delete($table);
        $this->_reset_write();

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Truncate.
     *
     * Compiles a truncate string and runs the query
     * If the database does not support the truncate() command
     * This function maps to "DELETE FROM table"
     *
     * @param	string	the table to truncate
     *
     * @return object
     */
    public function truncate($table = '')
    {
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        } else {
            $table = $this->_protect_identifiers($table, true, null, false);
        }
        $sql = $this->_truncate($table);
        $this->_reset_write();

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Delete.
     *
     * Compiles a delete string and runs the query
     *
     * @param	mixed	the table(s) to delete from. String or array
     * @param	mixed	the where clause
     * @param	mixed	the limit clause
     * @param	bool
     *
     * @return object
     */
    public function delete($table = '', $where = '', $limit = null, $reset_data = true)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        if ('' == $table) {
            if (!isset($this->ar_from[0])) {
                if ($this->db_debug) {
                    return $this->display_error('db_must_set_table');
                }

                return false;
            }
            $table = $this->ar_from[0];
        } elseif (is_array($table)) {
            foreach ($table as $single_table) {
                $this->delete($single_table, $where, $limit, false);
            }
            $this->_reset_write();

            return;
        } else {
            $table = $this->_protect_identifiers($table, true, null, false);
        }
        if ('' != $where) {
            $this->where($where);
        }
        if (null != $limit) {
            $this->limit($limit);
        }
        if (0 == count($this->ar_where) && 0 == count($this->ar_wherein) && 0 == count($this->ar_like)) {
            if ($this->db_debug) {
                return $this->display_error('db_del_must_use_where');
            }

            return false;
        }
        $sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
        if ($reset_data) {
            $this->_reset_write();
        }

        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * DB Prefix.
     *
     * Prepends a database prefix if one exists in configuration
     *
     * @param	string	the table
     *
     * @return string
     */
    public function dbprefix($table = '')
    {
        if ('' == $table) {
            $this->display_error('db_table_name_required');
        }

        return $this->dbprefix.$table;
    }

    // --------------------------------------------------------------------

    /**
     * Set DB Prefix.
     *
     * Set's the DB Prefix to something new without needing to reconnect
     *
     * @param	string	the prefix
     *
     * @return string
     */
    public function set_dbprefix($prefix = '')
    {
        return $this->dbprefix = $prefix;
    }

    // --------------------------------------------------------------------

    /**
     * Track Aliases.
     *
     * Used to track SQL statements written with aliased tables.
     *
     * @param	string	The table to inspect
     *
     * @return string
     */
    protected function _track_aliases($table)
    {
        if (is_array($table)) {
            foreach ($table as $t) {
                $this->_track_aliases($t);
            }

            return;
        }
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (false !== strpos($table, ',')) {
            return $this->_track_aliases(explode(',', $table));
        }
        // if a table alias is used we can recognize it by a space
        if (false !== strpos($table, ' ')) {
            // if the alias is written with the AS keyword, remove it
            $table = preg_replace('/\s+AS\s+/i', ' ', $table);
            // Grab the alias
            $table = trim(strrchr($table, ' '));
            // Store the alias, if it doesn't already exist
            if (!in_array($table, $this->ar_aliased_tables)) {
                $this->ar_aliased_tables[] = $table;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Compile the SELECT statement.
     *
     * Generates a query string based on which functions were used.
     * Should not be called directly.  The get() function calls it.
     *
     * @return string
     */
    protected function _compile_select($select_override = false)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        // ----------------------------------------------------------------
        // Write the "select" portion of the query
        if (false !== $select_override) {
            $sql = $select_override;
        } else {
            $sql = (!$this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';
            if (0 == count($this->ar_select)) {
                $sql .= '*';
            } else {
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather then in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->ar_select as $key => $val) {
                    $no_escape = isset($this->ar_no_escape[$key]) ? $this->ar_no_escape[$key] : null;
                    $this->ar_select[$key] = $this->_protect_identifiers($val, false, $no_escape);
                }
                $sql .= implode(', ', $this->ar_select);
            }
        }
        // ----------------------------------------------------------------
        // Write the "FROM" portion of the query
        if (count($this->ar_from) > 0) {
            $sql .= "\nFROM ";
            $sql .= $this->_from_tables($this->ar_from);
        }
        // ----------------------------------------------------------------
        // Write the "JOIN" portion of the query
        if (count($this->ar_join) > 0) {
            $sql .= "\n";
            $sql .= implode("\n", $this->ar_join);
        }
        // ----------------------------------------------------------------
        // Write the "WHERE" portion of the query
        if (count($this->ar_where) > 0 or count($this->ar_like) > 0) {
            $sql .= "\nWHERE ";
        }
        $sql .= implode("\n", $this->ar_where);
        // ----------------------------------------------------------------
        // Write the "LIKE" portion of the query
        if (count($this->ar_like) > 0) {
            if (count($this->ar_where) > 0) {
                $sql .= "\nAND ";
            }
            $sql .= implode("\n", $this->ar_like);
        }
        // ----------------------------------------------------------------
        // Write the "GROUP BY" portion of the query
        if (count($this->ar_groupby) > 0) {
            $sql .= "\nGROUP BY ";
            $sql .= implode(', ', $this->ar_groupby);
        }
        // ----------------------------------------------------------------
        // Write the "HAVING" portion of the query
        if (count($this->ar_having) > 0) {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->ar_having);
        }
        // ----------------------------------------------------------------
        // Write the "ORDER BY" portion of the query
        if (count($this->ar_orderby) > 0) {
            $sql .= "\nORDER BY ";
            $sql .= implode(', ', $this->ar_orderby);
            if (false !== $this->ar_order) {
                $sql .= ('desc' == $this->ar_order) ? ' DESC' : ' ASC';
            }
        }
        // ----------------------------------------------------------------
        // Write the "LIMIT" portion of the query
        if (is_numeric($this->ar_limit)) {
            $sql .= "\n";
            $sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Object to Array.
     *
     * Takes an object as input and converts the class variables to array key/vals
     *
     * @param	object
     *
     * @return array
     */
    public function _object_to_array($object)
    {
        if (!is_object($object)) {
            return $object;
        }
        $array = [];
        foreach (get_object_vars($object) as $key => $val) {
            // There are some built in keys we need to ignore for this conversion
            if (!is_object($val) && !is_array($val) && '_parent_name' != $key) {
                $array[$key] = $val;
            }
        }

        return $array;
    }

    // --------------------------------------------------------------------

    /**
     * Object to Array.
     *
     * Takes an object as input and converts the class variables to array key/vals
     *
     * @param	object
     *
     * @return array
     */
    public function _object_to_array_batch($object)
    {
        if (!is_object($object)) {
            return $object;
        }
        $array = [];
        $out = get_object_vars($object);
        $fields = array_keys($out);
        foreach ($fields as $val) {
            // There are some built in keys we need to ignore for this conversion
            if ('_parent_name' != $val) {
                $i = 0;
                foreach ($out[$val] as $data) {
                    $array[$i][$val] = $data;
                    ++$i;
                }
            }
        }

        return $array;
    }

    // --------------------------------------------------------------------

    /**
     * Start Cache.
     *
     * Starts AR caching
     *
     * @return void
     */
    public function start_cache()
    {
        $this->ar_caching = true;
    }

    // --------------------------------------------------------------------

    /**
     * Stop Cache.
     *
     * Stops AR caching
     *
     * @return void
     */
    public function stop_cache()
    {
        $this->ar_caching = false;
    }

    // --------------------------------------------------------------------

    /**
     * Flush Cache.
     *
     * Empties the AR cache
     *
     * @return void
     */
    public function flush_cache()
    {
        $this->_reset_run([
            'ar_cache_select' => [],
            'ar_cache_from' => [],
            'ar_cache_join' => [],
            'ar_cache_where' => [],
            'ar_cache_like' => [],
            'ar_cache_groupby' => [],
            'ar_cache_having' => [],
            'ar_cache_orderby' => [],
            'ar_cache_set' => [],
            'ar_cache_exists' => [],
            'ar_cache_no_escape' => [],
        ]);
    }

    // --------------------------------------------------------------------

    /**
     * Merge Cache.
     *
     * When called, this function merges any cached AR arrays with
     * locally called ones.
     *
     * @return void
     */
    protected function _merge_cache()
    {
        if (0 == count($this->ar_cache_exists)) {
            return;
        }
        foreach ($this->ar_cache_exists as $val) {
            $ar_variable = 'ar_'.$val;
            $ar_cache_var = 'ar_cache_'.$val;
            if (0 == count($this->$ar_cache_var)) {
                continue;
            }
            $this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
        }
        // If we are "protecting identifiers" we need to examine the "from"
        // portion of the query to determine if there are any aliases
        if (true === $this->_protect_identifiers and count($this->ar_cache_from) > 0) {
            $this->_track_aliases($this->ar_from);
        }
        $this->ar_no_escape = $this->ar_cache_no_escape;
    }

    // --------------------------------------------------------------------

    /**
     * Resets the active record values.  Called by the get() function.
     *
     * @param	array	An array of fields to reset
     *
     * @return void
     */
    protected function _reset_run($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value) {
            if (!in_array($item, $this->ar_store_array)) {
                $this->$item = $default_value;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Resets the active record values.  Called by the get() function.
     *
     * @return void
     */
    protected function _reset_select()
    {
        $ar_reset_items = [
            'ar_select' => [],
            'ar_from' => [],
            'ar_join' => [],
            'ar_where' => [],
            'ar_like' => [],
            'ar_groupby' => [],
            'ar_having' => [],
            'ar_orderby' => [],
            'ar_wherein' => [],
            'ar_aliased_tables' => [],
            'ar_no_escape' => [],
            'ar_distinct' => false,
            'ar_limit' => false,
            'ar_offset' => false,
            'ar_order' => false,
        ];
        $this->_reset_run($ar_reset_items);
    }

    // --------------------------------------------------------------------

    /**
     * Resets the active record "write" values.
     *
     * Called by the insert() update() insert_batch() update_batch() and delete() functions
     *
     * @return void
     */
    protected function _reset_write()
    {
        $ar_reset_items = [
            'ar_set' => [],
            'ar_from' => [],
            'ar_where' => [],
            'ar_like' => [],
            'ar_orderby' => [],
            'ar_keys' => [],
            'ar_limit' => false,
            'ar_order' => false,
        ];
        $this->_reset_run($ar_reset_items);
    }

    public function query($sql)
    {
        if (true === $this->save_queries) {
            $this->queries[] = $sql;
        }

        return $this->db->query($sql);
    }

    public function escape($value)
    {
        return $this->db->escape($value);
    }

    public function countAffected()
    {
        return $this->db->countAffected();
    }

    public function getLastId()
    {
        return $this->db->getLastId();
    }

    /**
     * Escape the SQL Identifiers.
     *
     * This function escapes column and table names
     *
     * @param	string
     *
     * @return string
     */
    public function _escape_identifiers($item)
    {
        if ('' == $this->_escape_char) {
            return $item;
        }
        foreach ($this->_reserved_identifiers as $id) {
            if (false !== strpos($item, '.'.$id)) {
                $str = $this->_escape_char.str_replace('.', $this->_escape_char.'.', $item);
                // remove duplicates if the user already included the escape
                return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
            }
        }
        if (false !== strpos($item, '.')) {
            $str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;
        } else {
            $str = $this->_escape_char.$item.$this->_escape_char;
        }
        // remove duplicates if the user already included the escape
        return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
    }

    // --------------------------------------------------------------------

    /**
     * From Tables.
     *
     * This function implicitly groups FROM tables so there is no confusion
     * about operator precedence in harmony with SQL standards
     *
     * @param	type
     *
     * @return type
     */
    public function _from_tables($tables)
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }

        return '('.implode(', ', $tables).')';
    }

    /**
     * Query result.  Acts as a wrapper function for the following functions.
     *
     * @param	string	can be "object" or "array"
     *
     * @return mixed either a result object or array
     */
    public function result($type = 'object')
    {
        if ('array' == $type) {
            return $this->result_array();
        } elseif ('object' == $type) {
            return $this->result_object();
        } else {
            return $this->custom_result_object($type);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Custom query result.
     *
     * @param class_name A string that represents the type of object you want back
     *
     * @return array of objects
     */
    public function custom_result_object($class_name)
    {
        if (array_key_exists($class_name, $this->custom_result_object)) {
            return $this->custom_result_object[$class_name];
        }
        if (false === $this->result_id or 0 == $this->num_rows()) {
            return [];
        }
        // add the data to the object
        $this->_data_seek(0);
        $result_object = [];
        while ($row = $this->_fetch_object()) {
            $object = new $class_name();
            foreach ($row as $key => $value) {
                $object->$key = $value;
            }
            $result_object[] = $object;
        }
        // return the array
        return $this->custom_result_object[$class_name] = $result_object;
    }

    // --------------------------------------------------------------------

    /**
     * Query result.  "object" version.
     *
     * @return object
     */
    public function result_object()
    {
        if (count($this->result_object) > 0) {
            return $this->result_object;
        }
        // In the event that query caching is on the result_id variable
        // will return FALSE since there isn't a valid SQL resource so
        // we'll simply return an empty array.
        if (false === $this->result_id or 0 == $this->num_rows()) {
            return [];
        }
        $this->_data_seek(0);
        while ($row = $this->_fetch_object()) {
            $this->result_object[] = $row;
        }

        return $this->result_object;
    }

    // --------------------------------------------------------------------

    /**
     * Query result.  "array" version.
     *
     * @return array
     */
    public function result_array()
    {
        if (count($this->result_array) > 0) {
            return $this->result_array;
        }
        // In the event that query caching is on the result_id variable
        // will return FALSE since there isn't a valid SQL resource so
        // we'll simply return an empty array.
        if (false === $this->result_id or 0 == $this->num_rows()) {
            return [];
        }
        $this->_data_seek(0);
        while ($row = $this->_fetch_assoc()) {
            $this->result_array[] = $row;
        }

        return $this->result_array;
    }

    // --------------------------------------------------------------------

    /**
     * Query result.  Acts as a wrapper function for the following functions.
     *
     * @param	string
     * @param	string	can be "object" or "array"
     *
     * @return mixed either a result object or array
     */
    public function row($n = 0, $type = 'object')
    {
        if (!is_numeric($n)) {
            // We cache the row data for subsequent uses
            if (!is_array($this->row_data)) {
                $this->row_data = $this->row_array(0);
            }
            // array_key_exists() instead of isset() to allow for MySQL NULL values
            if (array_key_exists($n, $this->row_data)) {
                return $this->row_data[$n];
            }
            // reset the $n variable if the result was not achieved
            $n = 0;
        }
        if ('object' == $type) {
            return $this->row_object($n);
        } elseif ('array' == $type) {
            return $this->row_array($n);
        } else {
            return $this->custom_row_object($n, $type);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Assigns an item into a particular column slot.
     *
     * @return object
     */
    public function set_row($key, $value = null)
    {
        // We cache the row data for subsequent uses
        if (!is_array($this->row_data)) {
            $this->row_data = $this->row_array(0);
        }
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->row_data[$k] = $v;
            }

            return;
        }
        if ('' != $key and !is_null($value)) {
            $this->row_data[$key] = $value;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Returns a single result row - custom object version.
     *
     * @return object
     */
    public function custom_row_object($n, $type)
    {
        $result = $this->custom_result_object($type);
        if (0 == count($result)) {
            return $result;
        }
        if ($n != $this->current_row and isset($result[$n])) {
            $this->current_row = $n;
        }

        return $result[$this->current_row];
    }

    /**
     * Returns a single result row - object version.
     *
     * @return object
     */
    public function row_object($n = 0)
    {
        $result = $this->result_object();
        if (0 == count($result)) {
            return $result;
        }
        if ($n != $this->current_row and isset($result[$n])) {
            $this->current_row = $n;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
     * Returns a single result row - array version.
     *
     * @return array
     */
    public function row_array($n = 0)
    {
        $result = $this->result_array();
        if (0 == count($result)) {
            return $result;
        }
        if ($n != $this->current_row and isset($result[$n])) {
            $this->current_row = $n;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
     * Returns the "first" row.
     *
     * @return object
     */
    public function first_row($type = 'object')
    {
        $result = $this->result($type);
        if (0 == count($result)) {
            return $result;
        }

        return $result[0];
    }

    // --------------------------------------------------------------------

    /**
     * Returns the "last" row.
     *
     * @return object
     */
    public function last_row($type = 'object')
    {
        $result = $this->result($type);
        if (0 == count($result)) {
            return $result;
        }

        return $result[count($result) - 1];
    }

    // --------------------------------------------------------------------

    /**
     * Returns the "next" row.
     *
     * @return object
     */
    public function next_row($type = 'object')
    {
        $result = $this->result($type);
        if (0 == count($result)) {
            return $result;
        }
        if (isset($result[$this->current_row + 1])) {
            ++$this->current_row;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
     * Returns the "previous" row.
     *
     * @return object
     */
    public function previous_row($type = 'object')
    {
        $result = $this->result($type);
        if (0 == count($result)) {
            return $result;
        }
        if (isset($result[$this->current_row - 1])) {
            --$this->current_row;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
     * The following functions are normally overloaded by the identically named
     * methods in the platform-specific driver -- except when query caching
     * is used.  When caching is enabled we do not load the other driver.
     * These functions are primarily here to prevent undefined function errors
     * when a cached result object is in use.  They are not otherwise fully
     * operational due to the unavailability of the database resource IDs with
     * cached results.
     */
    public function num_rows()
    {
        return $this->num_rows;
    }

    public function num_fields()
    {
        return 0;
    }

    public function list_fields()
    {
        return [];
    }

    public function field_data()
    {
        return [];
    }

    public function free_result()
    {
        return true;
    }

    protected function _data_seek()
    {
        return true;
    }

    protected function _fetch_assoc()
    {
        return [];
    }

    protected function _fetch_object()
    {
        return [];
    }

    /**
     * Tests whether the string has an SQL operator.
     *
     * @param	string
     *
     * @return bool
     */
    public function _has_operator($str)
    {
        $str = trim($str);
        if (!preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str)) {
            return false;
        }

        return true;
    }

    public function last_query()
    {
        return end($this->queries);
    }

    protected function _insert($table, $keys, $values)
    {
        return 'INSERT INTO '.$table.' ('.implode(', ', $keys).') VALUES ('.implode(', ', empty($values) ? null : $values).')';
    }

    /**
     * LIMIT string.
     *
     * Generates a platform-specific LIMIT clause.
     *
     * @param string $sql SQL Query
     *
     * @return string
     */
    protected function _limit($sql)
    {
        return $sql.' LIMIT '.($this->ar_offset ? $this->ar_offset.', ' : '').$this->ar_limit;
    }

    // --------------------------------------------------------------------

    /**
     * Update statement.
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @param	string	the table name
     * @param	array	the update data
     *
     * @return string
     */
    protected function _update($table, $values)
    {
        foreach ($values as $key => $val) {
            $valstr[] = $key.' = '.$val;
        }

        return 'UPDATE '.$table.' SET '.implode(', ', $valstr)
            .$this->_compile_wh('qb_where')
            .$this->_compile_order_by()
            .($this->ar_limit ? ' LIMIT '.$this->ar_limit : '');
    }

    // --------------------------------------------------------------------

    /**
     * Escape LIKE String.
     *
     * Calls the individual driver for platform
     * specific escaping for LIKE conditions
     *
     * @param	string|string[]
     *
     * @return mixed
     */
    public function escape_like_str($str)
    {
        return $this->escape_str($str, true);
    }

    // --------------------------------------------------------------------

    /**
     * Escape String.
     *
     * @param string|string[] $str  Input string
     * @param bool            $like Whether or not the string will be used in a LIKE condition
     *
     * @return string
     */
    public function escape_str($str, $like = false)
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_str($val, $like);
            }

            return $str;
        }

        $str = $this->_escape_str($str);

        // escape LIKE condition wildcards
        if (true === $like) {
            return str_replace(
                [$this->_like_escape_chr, '%', '_'],
                [$this->_like_escape_chr.$this->_like_escape_chr, $this->_like_escape_chr.'%', $this->_like_escape_chr.'_'],
                $str
            );
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Platform-dependant string escape.
     *
     * @param	string
     *
     * @return string
     */
    protected function _escape_str($str)
    {
        return str_replace("'", "''", $this->remove_invisible_characters($str));
    }

    /**
     * Remove Invisible Characters.
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     *
     * @return string
     */
    public function remove_invisible_characters($str, $url_encoded = true)
    {
        $non_displayables = [];

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

    public function group_start($not = '', $type = 'AND ')
    {
        $type = $this->_group_get_type($type);

        $this->qb_where_group_started = true;
        $prefix = (0 === count($this->qb_where) && 0 === count($this->qb_cache_where)) ? '' : $type;
        $where = [
            'condition' => $prefix.$not.str_repeat(' ', ++$this->qb_where_group_count).' (',
            'escape' => false,
        ];

        $this->qb_where[] = $where;
        if ($this->qb_caching) {
            $this->qb_cache_where[] = $where;
        }

        return $this;
    }

    protected function _group_get_type($type)
    {
        if ($this->qb_where_group_started) {
            $type = '';
            $this->qb_where_group_started = false;
        }

        return $type;
    }

    /**
     * Ends a query group.
     *
     * @return CI_DB_query_builder
     */
    public function group_end()
    {
        $this->qb_where_group_started = false;
        $where = [
            'condition' => str_repeat(' ', $this->qb_where_group_count--).')',
            'escape' => false,
        ];

        $this->qb_where[] = $where;
        if ($this->qb_caching) {
            $this->qb_cache_where[] = $where;
        }

        return $this;
    }
}
