<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("db_actions.php");

class TemplateModerator
{
    private  static function add_parts_into______card_designer_templates_parts($template_row_data)
    {
        for($i=0;$i<$_POST["total_parts"];$i++)
        {
            Db_Actions::DbInsert("
                INSERT INTO card_designer_templates_parts(card_designer_templates_id,product_id,product_price)
                VALUES(
                '".$template_row_data->id."',
                '".$_POST["product_id_".$i]."',
                '".$_POST["price_".$i]."'
                )
            ");
        }
    }
    /*
     * When no template
     */
    public static function create_and_save_template()
    {       
        Db_Actions::DbInsert("
                INSERT INTO card_designer_templates(user_id)
                VALUES('-1')
            ");
        $lasttempalte = Db_Actions::DbSelectLastRow("card_designer_templates");
        self::add_parts_into______card_designer_templates_parts($lasttempalte);
        print $lasttempalte->id;
    }
    /*
     * For opened template, when do reseving
     */
    public static function save_template()
    { 
        Db_Actions::DbDelete("
            DELETE FROM card_designer_templates_parts 
            WHERE card_designer_templates_id='".$_POST["card_designer_templates_id"]."'
        ");
        $template = Db_Actions::DbSelectRowByID("card_designer_templates", $_POST["card_designer_templates_id"]);
        self::add_parts_into______card_designer_templates_parts($template);
        print $_POST["card_designer_templates_id"];
    }
    public static function open_template()
    {    
        Db_Actions::DbSelect("
            SELECT * FROM card_designer_templates_parts 
            WHERE 
            card_designer_templates_id='".$_POST["card_designer_templates_id"]."'
        ");
        $all_rows_products = Db_Actions::DbGetResults('array');
        //print_r($all_rows_products);
        $source_xml_data = "<source_template>";
        $source_xml_data .= "<template_id>".$_POST["card_designer_templates_id"]."</template_id>";
        $source_xml_data .= "<parts>";
        for($i=0;$i<count($all_rows_products);$i++)
        {
            $source_xml_data .= "<part><product_id>".$all_rows_products[$i]["product_id"]."</product_id><price>".$all_rows_products[$i]["product_price"]."</price></part>";
        }
        $source_xml_data .= "</parts>";
        $source_xml_data .= "</source_template>";
        print $source_xml_data;
    }
}

if($_POST["action"]=="create_and_save_template")
{
    TemplateModerator::create_and_save_template();
}
if($_POST["action"]=="save_template")
{
    TemplateModerator::save_template();
}
if($_POST["action"]=="open_template")
{
    TemplateModerator::open_template();
}

?>
