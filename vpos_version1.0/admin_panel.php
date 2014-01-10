<?php
// PsychStudioXpress provides tools to behavioral and social science researchers.
// Copyright (C) 2013 William Kelly Hudgins
// This program is free software: you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by
// the Free Software Foundation, version 3.
//
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
// or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
// more details.
//
// You should have received a copy of the GNU General Public License along with
// this program. If not, see <http://www.gnu.org/licenses>.
//
// If you have questions, please email wkhudgins@psychstudioxpress.net

// vPOS
// Version 1.0
// admin_panel.php
// This file is used to monitor participant performance and guide supervisors.                 
// Sets session variables including:                                                              
// is_supervisor - Boolean value representing if the supervisor is a supervisor.                  
// Supervisor ID (SID) - The Supervisor's ID.                                                   
// Files called:
// admin_panel.php - The performance parameters

// Known bugs:
// 		Does not work for average mistakes or total mistakes

include 'conf.php';

class Order
{
    private $item;
    private $raw_modifications;
    private $price;
    
    /* Represent an order. */
    function __construct($order, $modifications, $price)
    {
        /* Create an order, storing the order number(?), items ordred,
        any modifications to any items, and teh price of the order. */
        $this->items = explode(';', $order);
        $this->raw_modifications = $modifications;
        $this->price = $price;
    }

    public function compare_items($other)
    {
        /* Compare the items in one order to the items in another order. */
        $missing_frm_other = array_diff($this->items, $other->items);
        $missing_frm_self = array_diff($other->items, $this->items);
        $items_differences = count($missing_frm_other) + count($missing_frm_self);
        return $items_differences;
    }        
    public function compare_modifications($other)
    {
        /* Compare the modifications in one order to the modifications in another. */
        $list_self_modifications = explode(',', $this->raw_modifications);
        $modification_differences = 0;
        
        # Get other item IDs
        $list_other_modifications = explode(',', $other->raw_modifications);
        $other_modifications_IDs = array();
        foreach($list_other_modifications as $other_modification)
        {
            $other_modification = explode('|', $other_modification, 2);
            $other_modification_ID = $other_modification[0];
            $other_modifications_IDs[] = $other_modification_ID;
        }
        $index = 0;
        foreach ($list_self_modifications as $modification)
        {
            $modification = explode('|', $modification, 2);
            $itemID = $modification[0];
            $modifications_list = explode('~', $modification[1]);
            
            if (array_search($itemID, $other_modifications_IDs) !== FALSE)
            {
                foreach ($list_other_modifications as $other_modifications)
                {
                    $other_modification = explode('|', $other_modifications, 2);
                    $other_modification_ID = $other_modification[0];
                    if ($other_modification_ID == $itemID)
                    {
                        $other_modifications_list = explode('~', $other_modification[1]);
                        $missing_from_self = array_diff($modifications_list, $other_modifications_list);
                        $missing_from_other = array_diff($other_modifications_list, $modifications_list);
                        $modification_differences += count($missing_from_self) + count($missing_from_other);
                        unset($other_modifications_IDs[$index]);
                        unset($list_other_modifications[$index]);
                        $index += 1;
                    }
                }
            }
            else
            {
                $modification_differences += count($modifications_list);
            }            
        }        
        
        if (count($other_modifications_IDs) > 0)
        {
            foreach ($list_other_modifications as $other_modifications)
            {
                $other_modification = explode('|', $other_modifications, 2);
                $other_modifications_list = explode('~', $other_modification[-1]);
                $modification_differences += count($other_modifications_list);
            }

        }    
        return $modification_differences;
    }
    
    public function compare_price($other)
    {
        /* Compare the price of two orders. */
        return abs($other->price - $this->price);
    }
    
    public function compare_orders($other)
    {
        /* Return the number of item and modification discrepentcies between two orders. */
        return $this->compare_items($other) + $this->compare_modifications($other);
    }
        
    public function order_differences($other)
    {
        /* Return the differences between two orders.*/
        return array($this->compare_orders($other), $this->compare_price($other));
    }
}
       
class Participant_Enterd_Order extends Order
{
    private $state_time;
    private $store_time;
    private $repeats;
    private $reductions;
    
    /* Represents a participant entered order. */    
    function __construct($order, $modifications, $price, $start_time, $store_time, $repeats, $reductions)
    {
        /* Create a user entered order as a child of the order class. */
        parent::__construct($order, $modifications, $price);
        $this->time = $store_time - $start_time;
        $this->repeats = $repeats;
        $this->reductions = $reductions;
    }
    
    public function get_time()
    {
        /* Return time it took to take the order. */
        return $this->time;
    }
    
    public function get_repeats()
    {
        /* Return times the order was repeated, if at all. */
        return $this->repeats;
    }
    
    public function get_reductions()
    {
        /* Return number of reductions on the order. */
        return $this->reductions;
    }
        
    public function results($other)
    {
        /* Return the differences between two orders and the time to take order. */
        return array($this->order_differences($other), $this->time);
    }
}

function determine_bgcolor($field, $value)
{
    include 'admin_panel_parameters.php';
    $excel = 'MAX_'.$field.'_EXCEL';
    $acceptable = 'MAX_'.$field.'_ACCEPT';
    $dangerzone = 'MAX_'.$field.'_DANGERZONE';
    // Excellent
    if  ($value <= $$excel)
    {
    $bgcolor = 'EAC117';
    }
    // Acceptable
    else if ($$excel < $value and $value <= $$acceptable)
    {
    $bgcolor = '008000';
    }
    // Dangerzone
    else if ($$acceptable < $value and $value <= $$dangerzone)
    {
    $bgcolor = 'FF8800';
    }
    // Absolutely unacceptable
    else if ($$dangerzone < $value)
    {
    $bgcolor = 'FF0000';
    }
    return $bgcolor;
}

// Temporary form of authentication    
if ($_GET['mann'] == 'whitney')
{
$is_supervisor = True;
$_SESSION['SID'] = $_GET['sid'];
}

# If the user is verified
if ($is_supervisor)
    {
    echo "<table border=\"1\">
            <tr>
                <th>Workstation</th>
                <th>Total number of mistakes</th>
                <th>Average # of mistakes</th>
                <th>Average order taking time</th>
                <th>Total Repeats</th>
                <th>Total Reductions</th>
                <th>Cash differential</th>    
                
            </tr>";

    $get_participant_IDs = mysql_query("SELECT distinct participant_ID from orders WHERE supervisor_ID = '$_SESSION[SID]'")or die(mysql_error());    
    while ($participants = mysql_fetch_array($get_participant_IDs))
        {
        foreach ($participants as $participant)
            {
            $orders = mysql_query("SELECT * FROM orders WHERE participant_ID = '$participant'")or die(mysql_error());
            
            $sum_mistakes = 0;
            $sum_time = 0;
            $sum_repeats = 0;
            $total_reductions = 0;
            $cash_diff = 0;
            $count = 0;		

            
            while ($order = mysql_fetch_array($orders))
                {        
                    $participant_order = new Participant_Enterd_Order($order['items'], $order['modifications'], $order['price'], $order['start_time'], 
                    $order['stored_time'], $order['repeats'], $order['reductions']);
                    $get_correct_order = mysql_query("SELECT * FROM correct_orders WHERE oid='$order[oid]'")or die(mysql_error());
                    $correct_order = mysql_fetch_array($get_correct_order);
                    $correct_order = new Order($correct_order['items'], $correct_order['modifications'], $correct_order['price']);

                    $item_mistakes = $participant_order->compare_items($correct_order);
                    $modification_mistakes = $participant_order->compare_modifications($correct_order);
                    $sum_mistakes += $item_mistakes + $modification_mistakes;
                    $sum_time += $participant_order->get_time();
                    $total_reductions += $participant_order->get_reductions();
                    $sum_repeats += $participant_order->get_repeats();
                    $cash_diff += $participant_order->compare_price($correct_order);
                    $workstation = $order['workstation'];
                    $count += 1;
                }

        $avg_mistakes = $sum_mistakes / $count;
        $avg_time = $sum_time / $count;
        
        echo "<tr>
                <td>".$workstation."</td>
                <td bgcolor='".determine_bgcolor('SUM_MISTAKES', $sum_mistakes)."'>".$sum_mistakes."</td>
                <td bgcolor='".determine_bgcolor('AVG_MISTAKES', $avg_mistakes)."'>".$avg_mistakes."</td>
                <td bgcolor='".determine_bgcolor('ORDER_TAKING_TIME', $avg_time)."'>".$avg_time."</td>
                <td bgcolor='".determine_bgcolor('REPEATS', $sum_repeats)."'>".$sum_repeats."</td>
                <td bgcolor='".determine_bgcolor('REDUCTIONS', $total_reductions)."'>".$total_reductions."</td>
                <td bgcolor='".determine_bgcolor('CASH_DIFF', $cash_diff)."'>".$cash_diff."</td>
            </tr>";
        break; # Why do I need? It works, but I don't get it.
            }
        }
    echo "</table>";
    }

// Fake empty page for people who aren't authenticated, must be adjusted to different servers.
else 
    {
echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>40-4 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL /admin_panel.php was not found on this server.</p>
</body></html>";
    }
?> 
