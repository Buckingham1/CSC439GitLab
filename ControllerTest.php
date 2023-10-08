<?php
require_once 'Main.php';

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    private $model;
    private $view;
    private $sut;
    
    public function setUp() :void {
        $d = new YahtzeeDice();
        $this->model = new Yahtzee($d);
        $this->view = $this->createStub(YahtzeeView::class);
        $this->sut = new YahtzeeController($this->model, $this->view);
    } 
    /**
    * @covers \YahtzeeController::get_model
    */
    public function test_get_model(){
        $result = $this->sut->get_model();
        $this->assertNotNull($result);
    }
    /**
    * @covers::get_view
    */
    public function test_get_view(){
        $result = $this->sut->get_view();
        $this->assertNotNull($result);
    }
    
    public function test_get_possible_categories(){
        // values for array
        $categories = [
            'ones' => 0,
            'twos' => 0,
            'threes' => 0,
            'fours' => 0,
            'fives' => 0,
            'sixes' => 0,
            'three_of_a_kind' => 0,
            'four_of_a_kind' => 0,
            'full_house' => 0,
            'small_straight' => 0,
            'large_straight' => 0,
            'chance' => 0,
            'yahtzee' => 0 
        ];
	//compares arrays
    $this->assertEquals($categories, $this->sut->get_possible_categories());
    }

public function test_process_score_input(){
	//checks if exit and q return -1
        $result = $this->sut->process_score_input("exit");
        $this->assertEquals(-1, $result);
        $result = $this->sut->process_score_input("q");
        $this->assertEquals(-1, $result);

        $this->model->roll(5); 
        $result = $this->sut->process_score_input("ones");
        $this->assertEquals(0, $result);
        $this->assertNotNull($this->model->get_scorecard()["ones"]);

	// checks that "invalid_category" returns -2 indicating an error
        $this->model->clear_kept_dice();
        $result = $this->sut->process_score_input("invalid_category");
        $this->assertEquals(-2, $result);

	//checks if valid category will update that category to the score calculated from the model's kept_dice attribute
	$this->sut->update_scorecard("ones", 4);
        $this->assertEquals($this->sut->process_score_input("ones"), 0);
    }
    
    public function test_process_keep_input_exit(){
        //checks for exit and q to return -1
        $result = $this->sut->process_keep_input("exit");
        $this->assertEquals(-1,$result);
        $result = $this->sut->process_keep_input("q");
        $this->assertEquals(-1, $result);

       //checks for "all" to return 0
        $result = $this->sut->process_keep_input("all");
        $this->assertEquals(0, $result);

        //checks for "none", "pass" and "" to return -2
        $result = $this->sut->process_keep_input("none");
        $this->assertEquals(-2, $result);
        $result = $this->sut->process_keep_input("pass");
        $this->assertEquals(-2, $result);
        $result = $this->sut->process_keep_input("");
        $this->assertEquals(-2, $result);
    }
}
?>