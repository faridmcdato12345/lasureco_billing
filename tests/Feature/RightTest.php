<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Sec_right;
use Carbon\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RightTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    /** @test */
    public function test_a_rights_can_be_added(){
        $this->withoutExceptionHandling();
        $this->post('/api/rights',[
            'right_name' => 'Print Consumptionby Bracket per Town',
            'right_description' =>'Print Consumptionby Bracket per Town'
        ]);
        $rights = Sec_right::first();
        $this->assertEquals('Print Consumptionby Bracket per Town',$rights->right_name);
        $this->assertEquals('Print Consumptionby Bracket per Town',$rights->right_description);
    }
    /** @test */
    public function fields_are_required(){
        $this->withoutExceptionHandling();
        collect(['right_name','right_description'])->each(function($field){
            $response = $this->post('/api/rights',
            array_merge($this->data(),[$field => '']));
            $response->assertSessionHasErrors($field);
            $this->assertCount(320,Sec_right::all());
        });
    }
    /** @test */
    public function get_all_rights_data(){
        $this->withExceptionHandling();
        $this->get('/api/rights');
        $this->assertCount(320,Sec_right::all());
    }
    /** @test */
    public function get_specific_rights_data(){
        $this->withoutExceptionHandling();
        $rights = Sec_right::factory()->make();
        $response = $this->get('/api/rights/' . $rights->name);
        $response->assertJson([
            'right_name' => $rights->name,
            'right_description' => $rights->description
        ]);
    }
    public function data(){
        return [
            'right_name'=>'Print Consumptionby Bracket per Town',
            'right_description'=>'Print Consumptionby Bracket per Town'
        ];
    }
}
