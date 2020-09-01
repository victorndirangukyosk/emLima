<?php

class ModelSettingTestimonial extends Model
{
    public function addTestimonial($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."testimonial SET `name` = '".$this->db->escape($data['name'])."', image = '".$this->db->escape($data['image'])."', message='".$data['message']."', sort_order='".$data['sort_order']."', status='".$data['status']."'");
    }

    public function editTestimonial($testimonial_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."testimonial SET `name` = '".$this->db->escape($data['name'])."', image = '".$this->db->escape($data['image'])."', message='".$data['message']."', sort_order='".$data['sort_order']."', status='".$data['status']."' WHERE testimonial_id='".$testimonial_id."'");
    }

    public function deleteTestimonial($testimonial_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."testimonial WHERE testimonial_id = '".(int) $testimonial_id."'");
    }

    public function getTestimonial($testimonial_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."testimonial WHERE testimonial_id = '".(int) $testimonial_id."'");

        return $query->row;
    }

    public function getTestimonials()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'testimonial ORDER BY name');

        return  $query->rows;
    }

    public function getTotalTestimonials()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'testimonial');

        return $query->row['total'];
    }
}
