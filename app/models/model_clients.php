<?php
class Model_Clients extends Model {

  public function get_data() {
    $table = "<table id='clients' class=\"display table table-condensed table-striped table-hover table-bordered clients responsive pull-left\">";
      $table .= "<thead><tr><th>ID</th><th>Campaign name</th><th>Email</th><th>Full Name</th><th>Lead Cost</th><th>Delivery</th><th>Status</th><th>Actions</th></tr></thead>";
      $table .= "</table>";
      return $table;
  }

}
