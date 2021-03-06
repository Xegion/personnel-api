<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tp_model extends MY_Model {
    public $table = 'units';
    public $primary_key = 'units.id';
    
    public function default_select() {
        $this->db->select('SQL_CALC_FOUND_ROWS *', FALSE)
        ->select('(SELECT COUNT( DISTINCT( member_id )) FROM assignments WHERE unit_id = units.id ) AS member_count', FALSE)
        ->select("(SELECT CONCAT( DATE_FORMAT( MIN(datetime) ,'%Y-%m-%d'),' - ', DATE_FORMAT( MAX(datetime) ,'%Y-%m-%d')) FROM events WHERE events.unit_id = `units`.id )  AS days", FALSE)
        ->select("(SELECT Round( (SUM(attended)/COUNT(1))*100 ) FROM `attendance` WHERE `event_id` IN (SELECT `id` FROM `events` WHERE `unit_id` = `units`.`id`)) AS avg");
    }
    
    public function default_join() {
    }
    
    public function select_member() {
        
    }
    
    public function default_where() {
        $this->filter_where("units.class = 'Training' AND units.id <> 43");
    }
    
    public function default_order_by() {
        $this->db->order_by('units.order DESC, units.abbr DESC');
    }

    public function only_future_tps() {
        $this->filter_where("(SELECT MIN(datetime) FROM events WHERE events.unit_id = `units`.id ) >= DATE_FORMAT( NOW(), '%Y-%m-%d' ) ");
    }
    
    public function only_active() {
        $this->filter_where("`units`.`active`",1);
    }

    public function by_game($game) {
        $this->filter_where('`units`.`game`', $game);
        return $this;
    }
    
    public function by_timezone($timezone) {
        $this->filter_where('units.timezone = \'' . $timezone . '\'');
        return $this;
    }
    
    
}
