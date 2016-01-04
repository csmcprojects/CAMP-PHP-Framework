<?php
namespace csmc\modules\csmc;

use csmc\native\uinterface\nav as nav;
use csmc\native\framework\ios as ios;
use csmc\native\framework\redirects as redirects;
use csmc\native\db\omysqli as omysqli;
class articles{
	
	public static $func_whitelist = array('categories', 'section', 'display');
	
	public static function csmc_setup(){
		nav::setButton("csmc_articles_categories", "Articles");
	}
	
	//Not required, use as intended
	public function __construct(){
		
	}
	public static function display(){
		if(isset($_SESSION["get_params"][0])){
			$omysqli = new omysqli();
			if($omysqli){
				if(($data = $omysqli->dataExecute("SELECT * FROM `article_content` WHERE title='".self::UtfToTitle($_SESSION["get_params"][0])."' LIMIT 0, 1024", "id,title,description,category,tags,content,author,publish_date,latest_edit_date")) !== false){
					if(empty($data)){
						redirects::error(404, "Article not found.");
					} else {
						$data = $data[0];
						$category = $omysqli->dataExecute("SELECT * FROM `article_category` WHERE id='".$omysqli->sanitize($data["category"])."'", "id,name");
						$author = $omysqli->dataExecute("SELECT * FROM `article_author` WHERE id='".$data["author"]."'", "id,name,description,google,facebook");					
						$category = $category[0]; $author = $author[0];
						$wrap = '<p># <a href="#!/csmc/articles/categories/">Categories</a> << <a href="#!/csmc/articles/section/?/'.$category["name"].'">'.$category["name"].'</a> << <a href="#!/csmc/articles/display/?/'.self::titleToUtf($data["title"]).'">'.$data["title"].'</a> </p>';
						$wrap .= '<div id="article_'.$data["id"].'" class="">
						<h1><a href="#!/csmc/articles/display/?/'.self::titleToUtf($data["title"]).'" >'.$data["title"].'</a></h1>
						<span>Tags: </span><strong>'.$data["tags"].'</strong><br><br>
						<span>'.$data["content"].'</span><br>
						<p>by <a href="'.$author["google"].'" rel="author">'.$author["name"].'</a> published on <span>'.$data["publish_date"].'</span> and last updated on <span>'.$data["latest_edit_date"].'</span></p>
						</div>';
					}
					
				}
			}
			return ios::out($wrap);
		} else {
			redirects::error(404, "Article not found.");
		}	
	}
	/**
	 * list - Lists all avaiable articles
	 **/
	public static function categories(){
		$wrap = '<p># <a href="#!/csmc/articles/categories/">Categories</a></p>';
		$omysqli = new omysqli();
		if($omysqli){
			if(($data = $omysqli->dataExecute("SELECT * FROM article_category", "id,name")) !== false){
				foreach ($data as $key => $value) {
					$wrap .= nav::getDesktopButton("csmc_articles_section!section=".$value["name"], $value["name"]);
				} 
			}
		}
		return ios::out($wrap);	
	}

	public static function section(){
		if(isset($_SESSION["post_params"]["section"]) || isset($_SESSION["get_params"][0])){
			if(isset($_SESSION["post_params"]["section"])){ $categoryName = $_SESSION["post_params"]["section"]; } else { $categoryName = $_SESSION["get_params"][0];}
			$wrap = '<p># <a href="#!/csmc/articles/categories/">Categories</a> << <a href="#!/csmc/articles/section/?/'.$categoryName.'">'.$categoryName.'</a></p>';
			$wrap .= "<h2>".$categoryName."</h2><br>";
			$omysqli = new omysqli();
			$category = $omysqli->dataExecute("SELECT * FROM `article_category` WHERE name='".$omysqli->sanitize($categoryName)."'", "id,name");
			$category = $category[0];
			if(($data = $omysqli->dataExecute("SELECT * FROM `article_content` WHERE category='".$category["id"]."' LIMIT 0, 1024", "id,title,description,category,tags,content,author,publish_date,latest_edit_date")) !== false){
				if(empty($data)){
					$wrap .= "<p>No articles avaiable.</p>";
				} else {
					$i = 0;
					while($i < count($data)){
						if(isset($data[$i])){
							$data = $data[$i];
							$author = $omysqli->dataExecute("SELECT * FROM `article_author` WHERE id='".$data["author"]."'", "id,name,description,google,facebook");
							$author = $author[0];
							$wrap .= '<div id="article_'.$data["id"].'" class="article_box">
							<h1><a href="#!/csmc/articles/display/?/'.self::titleToUtf($data["title"]).'" >'.$data["title"].'</a></h1>
							<p> Tags: '.$data["tags"].'</p>
							<strong>'.$data["description"].'</strong>
							<p>by <a href="'.$author["google"].'" rel="author">'.$author["name"].'</a></p>
							</div>';
						}
						$i++;		
					}
				}				
			} else {
				return ios::out("", "An error occured ".$omysqli->error());
			}
			return ios::out($wrap);
		} else {
			redirects::error(404, "Category not found.");
		}
		
	}

	private static function titleToUtf($string){
		return trim(strtolower(str_replace(" ", "-", utf8_encode($string))));
	}
	private static function UtfToTitle($string){
		return trim(strtolower(str_replace("-", " ", utf8_decode($string))));
	}
}
