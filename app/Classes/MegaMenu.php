<?php

namespace App\Classes;

use App\Menu;
use Illuminate\Support\Facades\DB;

class MegaMenu {

    private $headerArray;

    function __construct() {
        //make array out of parent names in header
        $names = Menu::get('name');
        $rawMenuArray = json_decode($names, true);
        $menuArray = array();
        for ($i = 0;$i < count($rawMenuArray); $i++) {
          $menuArray[$i] = $rawMenuArray[$i]['name'];
        } 
        $this->headerArray = $menuArray;
    }
    
    //create megaMenu
    function menuCreation() {
        echo "<div id='headerRow' class='row p-0 header'>"; 
        //display menu from server
        for ($i = 1;$i < count($this->headerArray);++$i) {
            //build parent menu elements
            
        
            if ($this->headerArray[$i] != "Icons" ) {
                echo "<div class='col-md-4 col-sm-6 headerElement container-fluid '>";
                //resume element
                echo "<div class='row headerRow'>";
                echo "<div id='" . $this->headerArray[$i] . "'onclick='routeParent(this.innerHTML)' class='col-12 dropdownContent'>". $this->headerArray[$i];
                echo "</div></div>";
            } else {
                echo "<div class='col-md-4 col-sm-6 headerElement container-fluid hideForPhone'>";
                echo "<div class=' row'>";
                echo "<div class='  iconRow d-flex  centerMyIcons '>";
                echo    "<a href='https://github.com/heckwithu2?tab=projects' target='_blank'><img class='socialIcon icon' stye='min-width: 25px;' src='" . asset('public/images/github.png') . "'></a>";
                echo    "<a href='https://www.linkedin.com/in/jeremiah-heck-498b1a184/'  target='_blank'><img class='socialIcon icon' src='" . asset('public/images/linkedin2.png') . "'> </a>";                        
                echo    "<a href='mailto: jeremiah.heck7@gmail.com'><img class='socialIcon icon' src='" . asset('public/images/gmail.png') . "'></a>";
                echo "</div>";
                echo "</div>";
            }            

            
                //grab id of the parent menu
            $rawId = DB::table('menu')
            ->select('id')
            ->where('name', '=', $this->headerArray[$i])
            ->get();

            //clean raw JSON    
            $tmp = json_decode($rawId, true);
            $id = array();
            $id[0] = $tmp[0]['id'];

            //use the id to link the subcat table 
            $rawCats = DB::table('subcategories')
                ->select('sub_name')
                ->where('parent_id', '=', $id[0])
                ->get();

            //clean raw JSON
            $tmp = json_decode($rawCats, true);
            $subCats = array();
            $parentName = $this->headerArray[$i];

            if (count($tmp) > 0) {
                for ($x = 0;$x < count($tmp);++$x) {                

                    $subCats[$i] = $tmp[$x]['sub_name'];
                    //add child elements to curent parent
                    echo "<div class='row dropdownRow '>";
                    echo "<div id='" . $this->headerArray[$i] . "'onclick='routeChild(this.innerHTML,this.id)' class='col-12 dropdownContent'>" . $subCats[$i];
                    echo "</div></div>";
                }
            }
        echo "</div>";
           
        }
    }   
}

?>