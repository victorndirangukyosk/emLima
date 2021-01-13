<div id="recenttabs" class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i>Sales</h3>
  </div>
  <div class="panel-body">
    <nav>
      <ul class="nav nav-pills">
        <li class="active">
          <a data-toggle="tab" href="#dash_recent_orders">
            <i class="fa fa-fire"></i>
            <span class="hidden-inline-xs"><?php echo $text_last_order; ?></span>
          </a>
        </li>
      </ul>
    </nav>
    <div class="tab-content panel">
      <div id="dash_recent_orders" class="tab-pane active">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <td><?php echo $column_order_id; ?></td>
              <td><?php echo $column_customer; ?></td>
              <td><?php echo $column_status; ?></td>
              <td><?php echo $column_date_added; ?></td>
              <td><?php echo $column_total; ?></td>
              <td class="text-center"><?php echo $column_action; ?></td>
            </tr>
            </thead>
            <tbody id="recent_orders">
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
