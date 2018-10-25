<?php
/*
Plugin Name: Outgrow Quizzes
description: Outgrow provides Calculators, Quizzes and Polls which you have created at your account.
Setting up configurable fields for our plugin.
Author:  Shagun
Version: 1.0.0
*/


static $header_script1;

add_action("admin_menu", "final_outgrow_calci");

wp_register_script('my_plugin_script', plugins_url('/js/script.js', __FILE__), array(
    'jquery'
));
wp_enqueue_script('my_plugin_script');
wp_register_style('my-plugin-style', plugins_url('outgrow/css/style.css'));
wp_enqueue_style('my-plugin-style');
wp_localize_script('my_plugin2_script', 'myAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php')
));
wp_enqueue_script('my_plugin2_script');
register_activation_hook(__FILE__, 'wp_outgrow_calci_api_table');
register_activation_hook(__FILE__, 'wp_outgrow_calci_table');
register_deactivation_hook(__FILE__, 'deactivation_table');

//enqueues our external font awesome stylesheet
function enqueue_our_required_stylesheets(){
	wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/5.4.0/css/font-awesome.min.css'); 
}
add_action('wp_enqueue_scripts','enqueue_our_required_stylesheets');
// ends

function final_outgrow_calci()
{
    add_menu_page("header and footer", "Outgrow", "manage_options", 'final_outgrow_calci_menu', "final_outgrow_calci_script_page", '', 50);
}
// for api key
function wp_outgrow_calci_api_table()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    if (count($wpdb->get_var('SHOW TABLE LIKE "wp_outgrow_calci_api_table"') < 0)) {
        $sql_query_to_create_table = "CREATE TABLE `wp_outgrow_calci_api_table` (
            `api_key` varchar(160) NOT NULL ,
            PRIMARY KEY (api_key)
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($sql_query_to_create_table);
    }
}


function final_outgrow_calci_script_page()
{
?>
 
<?php
    
    // working..
    $header_script1 = get_option('final_outgrow_header_script1', 'Enter your API KEY');
    
    global $wpdb;
    $db_result = $wpdb->get_results('select * from wp_outgrow_calci_api_table');
    
?>

  <div class="main-section">
        <p class="main-heading">OUTGROW QUIZZES</p>
        <form name="form1" method="post" >
        <input type="text" name="header_script1" name="apiKey" class="large-text" id="apikey" placeholder=" ENTER API KEY">
         <button class="final_outgrow-data-button" id="final-outgrow" name="final_outgrow_calci_update">ADD</button>
       <br>
       </div>
    <?php
    
    
    global $wpdb;
    $optionAPI = "";
    $db_result = $wpdb->get_results('select * from wp_outgrow_calci_api_table');
    if ($db_result) {
?> 
     
        <div id="content">
        <select name="delete_item" id="select-id" onchange="apiChange()" placeholder="Select API KEY">
        <!-- <option value="invalid ">Select API KEY</option> -->
        <?php
        global $wpdb;
        $db_result = $wpdb->get_results('select * from wp_outgrow_calci_api_table');
        // print($optionAPI);
        if ($db_result) {
            ?>
            <?php
            foreach ($db_result as $db_row) {
            
?>
             <option><?php
                echo $db_row->api_key;
?></option>
            <?php
            }
        } else {
        }
?>
      </select>
        <button name="show_data" id="show-data">SHOW</button> 
        <button name="delete_data" id="delete-data">DELETE</button>
        </div> 
<?php
    }
?>
   </form>
    
    
    <?php
    global $wpdb;
    
    $repeatedAPI=0; //0 means false 1 means true
    if (isset($_POST['show_data'])) {
        $optionAPI = $_POST['delete_item'];
       $value=api_call($optionAPI);
       if($value == 401){
           $repeatedAPI=1;
       }else{
        $repeatedAPI=0;
       }
    } else {
        // print('<script>console.log(show_data[0]);</script>');
    }
    $db_result = $wpdb->get_results("select * from wp_outgrow_calci_table WHERE api_key='$optionAPI' ");
    if ($db_result && $repeatedAPI==0) {
        $calci    = 0;
        $quiz     = 0;
        $poll     = 0;
        $type     = array();
        $api_list = array();
        $i        = 0;
        foreach ($db_result as $db_row) {
            $type[$i]     = $db_row->calci_type;
            $api_list[$i] = $db_row->api_key;
            $i++;
        }
        $type          = array_unique($type);
        $api_list      = array_unique($api_list);
        $new_array     = array();
        $new_api_array = array();
        for ($j = 0; $j <= count($db_result); $j++) {
            if ($type[$j] != "") {
                array_push($new_array, $type[$j]);
            }
            if ($api_list[$j] != "") {
                array_push($new_api_array, $api_list[$j]);
            }
        }
?>
                 
    <?php
        $selectedAPI = "";
?>
    <div>
                    
    </select>
     </div>
                                <hr>
<?php
        for ($i = 0; $i < count($new_array); $i++) {
?>
             <button class="calci-view" onclick="result('<?php echo $new_array[$i]; ?>')" name="<?php 
                echo $new_array[$i]; ?>" id="<?php echo $new_array[$i]; ?>"><?php echo $new_array[$i]; ?>
             </button>
<?php
        }
?>
        <hr>
<?php
        $typo        = $new_array[0];
        $selectedAPI = $new_api_array[0];
        
        $data_output1 = $wpdb->get_results("select * from wp_outgrow_calci_table WHERE calci_type='Calculator' AND api_key='$optionAPI'");
        $data_output2 = $wpdb->get_results("select * from wp_outgrow_calci_table WHERE calci_type='Quiz' AND api_key='$optionAPI'");
        $data_output3 = $wpdb->get_results("select * from wp_outgrow_calci_table WHERE calci_type='Poll' AND api_key='$optionAPI'");
        if ($data_output1) {
?>
                       
                        
        <div id="result1">
        <div class="calci-card-row"> 
<?php
            foreach ($data_output1 as $db_row_data) {
                if ($db_row_data->short_url != "" && $db_row_data->id != "") {
?>
   
         <div class="calci-card-col">
            <div class="calci-card-body">
                <div class="calci-card-content">
                       <h4> <?php 
                    if ($db_row_data->title) { 
                        echo $db_row_data->title; 
                    } else { 
                        echo $db_row_data->name;
                    }?>
                    </h4>
                </div>
         <div class="card-footer">   
            <button  onclick='viewDetails(`<?php echo $db_row_data->id; ?>`, `<?php echo $db_row_data->data_url; ?>`,
        `<?php echo $db_row_data->short_url; ?>`)'> MORE DETAILS
        </button> 
        </div> 
            </div> 
        </div>             
          
                          
            
<?php
                }
            }
?>
</div>    
        </div>
        <?php
        }
        if ($data_output2) {
?>
                          
        <div id="result2">
                        
        <?php
            foreach ($data_output2 as $db_row_data) {
                if ($db_row_data->short_url != "" && $db_row_data->id != "") {
?>
   
         <div class="calci-card-col">
            <div class="calci-card-body">
                <div class="calci-card-content">
                       <h4> <?php 
                    if ($db_row_data->title) { 
                        echo $db_row_data->title; 
                    } else { 
                        echo $db_row_data->name;
                    }?>
                    </h4>
                </div>
         <div class="card-footer">   
            <button  onclick='viewDetails(`<?php echo $db_row_data->id; ?>`, `<?php echo $db_row_data->data_url; ?>`,
        `<?php echo $db_row_data->short_url; ?>`)'>GET MORE
        </button> 
        </div> 
            </div> 
        </div>             
                                
          
   
<?php
        }
     }
?>
        </div>
<?php
        }
        if ($data_output3) {
?>
                         
    <div id="result3">
    <?php
            foreach ($data_output3 as $db_row_data) {
                if ($db_row_data->short_url != "" && $db_row_data->id != "") {
?>
   
         <div class="calci-card-col">
            <div class="calci-card-body">
                <div class="calci-card-content">
                       <h4> <?php 
                    if ($db_row_data->title) { 
                        echo $db_row_data->title; 
                    } else { 
                        echo $db_row_data->name;
                    }?>
                    </h4>
                </div>
         <div class="card-footer">   
            <button  onclick='viewDetails(`<?php echo $db_row_data->id; ?>`, `<?php echo $db_row_data->data_url; ?>`,
        `<?php echo $db_row_data->short_url; ?>`)'>GET MORE
        </button> 
        </div> 
            </div> 
        </div>             
          
<?php
        }
    }
?>

    </div>
<?php
        }
?>
              
                
<?php
    } else {
        // print_r("<div class='not-found'>NO DATA FOUND</div>");
    }
    
?>
      
     </div>
        <div id="result2"></div>
    </div>
    

<!--  modal open -->
    <div id="myModal" class="modal" style="display: none; position: fixed; z-index: 1;  padding-top: 100px;  left: 0; top: 0; width: 100%;  height: 100%;overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4);">

       

        <!-- Modal content -->
        <div class="modal-content"  id="modal-content" style=" background-color: #fefefe;margin: auto;padding: 20px;border: 1px solid #888;width: 50%;">
        <span id="close" onclick="hide()" style="color: #aaaaaa;float: right;font-size: 28px;font-weight: bold;">&times;</span>
         <!-- adding more -->
         <div>
                <button class="modal-button" id="cat01" onclick="cat01()">EMBED + MOBILE FULL SCREEN</button>
                <button class="modal-button" id="cat02" onclick="cat02()">EMBED + MOBILE IN PAGE</button>
                <button class="modal-button" id="cat03" onclick="cat03()">POP UP</button>
            </div>
        <!-- ending adding more --> 
        <textarea id="text-inside" class="text-hidden" rows="10" style="margin-top:10px" onclick="copy()"  >
   
        </textarea>
        <div id="click-copy-text" class="hide">
            Copied.!
        </div>
        <button class="msg-copy" onclick="copy()">Copy To Clipboard</button>
        
        
    </div>
    
    </div>
<!-- modal ennd -->
   <?php
}
if (isset($_POST['final_outgrow_calci_update'])) {
    $apiKey     = $_POST['header_script1'];
    $table_name = 'wp_outgrow_calci_table';
    $headers    = array(
        'API-KEY' => $apiKey
    );
    // live api
    // var url="https://api.outgrow.co/api/v1/calculator?status=Live&type=All&sort=alpha_as";
    $request    = Requests::get('https://api-calc.outgrow.co/api/v1/calculator?status=BOTH&type=All&sort=alpha_as', $headers);
    // print_r($request->body);
    //testing
    // $request = Requests::get('https://outgrow-api1.herokuapp.com/api/v1/calculator?status=Both&type=All&sort=alpha_ass', $headers);
    $res        = json_decode($request->body);
    $res2       = json_encode($res->data);
    $res3       = json_decode($res2);
    
    $meta        = array();
    $calci_count = count($res->data);
    if ($res->success == 1) {
        //For API-KEY
        if ($wpdb->insert('wp_outgrow_calci_api_table', array(
            'api_key' => $apiKey
        )) == false) {
            // print_r('<script>alert("API KEY already exists")</script>');
            api_exist_call();
            // wp_die('Database Insertion failed');
        } else {
        }
        for ($i = 0; $i < $calci_count; $i++) {
           
            if ($wpdb->insert('wp_outgrow_calci_table', array(
                'name' => $res3[$i]->name,
                'api_key' => $apiKey,
                'id' => $res3[$i]->id,
                'data_url' => $res3[$i]->calc_url,
                'short_url' => $res3[$i]->short_url,
                'calci_type' => $res3[$i]->type,
                'url' => $res3[$i]->meta_data->url,
                'title' => $res3[$i]->meta_data->title
            )) == false) {
            } else {
            }

        }
    } else {
        // print_r("<script>alert('Invalid API')</script>");
        ?>
            <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            <strong>Invalid API - </strong> API does not exists.
        </div>
        <?php
    }
}
// add_shortcode('first', 'view');
// add_shortcode('first','final_outgrow_calci_script_page');
function wp_outgrow_calci_table()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    if (count($wpdb->get_var('SHOW TABLE LIKE "wp_outgrow_calci_table"') < 0)) {
        $sql_query_to_create_table = "CREATE TABLE `wp_outgrow_calci_table` (
            `name` varchar(50) NOT NULL,
            `api_key` varchar(160) NOT NULL ,
            `id` varchar(160),
            `data_url` varchar(160) NOT NULL UNIQUE,
            `short_url` varchar(160) NOT NULL,
            `calci_type` varchar(50) NOT NULL,
            `url` varchar(100) NOT NULL,
            `title` varchar(50) NOT NULL,
            PRIMARY KEY (data_url)
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($sql_query_to_create_table);
    }
}

echo "<div class='result'></div>";

function api_exist_call(){
    ?>
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            <strong>Already Exists</strong> Please try another API.
        </div>

    <?php
}

if (isset($_POST['delete_data'])) {
    $item = $_POST['delete_item'];
    
    if ($_POST['delete_item'] != "invalid") {
        global $wpdb;
        $wpdb->delete('wp_outgrow_calci_api_table', array(
            'api_key' => $item
        ));
        $wpdb->delete('wp_outgrow_calci_table', array(
            'api_key' => $item
        ));
        
    } else {
        print_r("<script>alert('Please choose API-KEY');</script>");
    }
}
function deactivation_table()
{
    global $wpdb;
    $wpdb->query('DROP table IF Exists wp_outgrow_calci_table');
    $wpdb->query('DROP table IF Exists wp_outgrow_calci_api_table');
}



?>  
<?php

//api calling fxn
function api_call($api){
    $headers    = array(
        'API-KEY' => $api
    );
     $req  = Requests::get('https://api-calc.outgrow.co/api/v1/calculator?status=BOTH&type=All&sort=alpha_as', $headers);
     $res0        = json_decode($req->body);
     $code       = json_encode($res0->code);
     if($code == 401){
        global $wpdb;
        $wpdb->delete('wp_outgrow_calci_api_table', array(
            'api_key' => $api
        ));
        $wpdb->delete('wp_outgrow_calci_table', array(
            'api_key' => $api
        ));
        ?>
     <div class="alert-api-key">
            <!-- <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  -->
            <span class="closebtn" onclick="location.reload();" required>&times;</span>
            <strong> API KEY EXPIRED..! </strong> Please try new API.
    </div>
   
        <?php
     }
    return $code;
    // $res3       = json_decode($res2);
    
}

function display_my_outgrow_calci($atts,$content,$tag){
	$values = shortcode_atts(array(
        'type' => '',
        'id' => '',
        'data_url' => '',
        'short_url' => ''
	),$atts);  
	
	
	//based on input determine what to return
	$output = '';
	  // $a=$values['id'];
        // $b=$values['data_url'];
        // $c=$values['_url']
        if($values["type"] == "mobile_full_screen" ){
            $output = "<div><div class='op-interactive' id='$values[id]' data-url='$values[data_url]' data-surl='$values[short_url]' data-width='100%'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/sloader.js'></script><script>initIframe('$values[id]');</script></div>";
        }else if($values["type"] == "mobile_in_page" ){
            $output = "<div><div class='op-interactive' id='$values[id]' data-url='$values[data_url]' data-surl='$values[short_url]' data-width='100%'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/nloader.js'></script><script>initIframe('$values[id]');</script></div>";
        }else if($values["type"] == "pop_up" ){
            // $output = "<div><div id='$values[id]' data-embedCookieDays='10' data-embedScheduling='false' data-embedTimed='true' data-embedExit='false' data-embedTimeFormat='0' data-embedTimeValue='5' data-embedBorderRadius='0' data-embedFontSize='12' data-textcolor='#fb5f66' data-bgcolor='#fb5f66' data-prop='outgrow-p' data-type='outgrow-l' data-url= data-url='$values[data_url]' data-text='Get Started'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/nloader.js'></script><script>initIframe('$values[id]');</script></div>";
            $output="<div><div id='$values[id]' data-embedCookieDays='10' data-embedScheduling='false' data-embedTimed='true' data-embedExit='false' data-embedTimeFormat='0' data-embedTimeValue='5' data-embedBorderRadius='0' data-embedFontSize='12' data-textcolor='#fb5f66' data-bgcolor='#fb5f66' data-prop='outgrow-p' data-type='outgrow-l'  data-url='$values[data_url]' data-text='Get Started'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/nloader.js'></script><script>initIframe('$values[id]');</script></div>";

        }
		
	return $output;
	
}


add_shortcode('outgrow','display_my_outgrow_calci');


?>