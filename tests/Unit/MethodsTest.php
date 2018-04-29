<?php
/**
 * Created by PhpStorm.
 * User: oza
 * Date: 29/04/18
 * Time: 14:30
 */

namespace Oza\UserImagesManager\Tests;


use App\Profile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MethodsTest extends \Tests\TestCase
{
    Use DatabaseMigrations;
    Use DatabaseTransactions;

    public $profile;
    public $methods;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->profile = Profile::create(['user_id' => 1]);
        $this->methods = $this->profile->avatarManager();
    }

    public function testSetImage()
    {

        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->assertEquals($img, $this->methods->current());
    }

    public function testRandomImage()
    {
        $img = $this->methods->setRandom();
        $this->assertEquals($img, $this->methods->current());
    }

    public function testMultipleAdding()
    {
        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->assertEquals(0, count($this->methods->others()));
        $this->methods->setRandom();
        $this->assertEquals(1, count($this->methods->others()));
    }

    public function testStructure()
    {
        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->assertArrayHasKey('current', $this->methods->all());
        $this->assertArrayHasKey('others', $this->methods->all());
        $this->assertArrayHasKey('src', (array)$this->methods->all()['current']);
        $this->assertArrayHasKey('id', (array)$this->methods->all()['current']);
        $this->assertArrayHasKey('set_at', (array)$this->methods->all()['current']);
        $this->methods->setRandom();
        $this->assertArrayHasKey('src', (array)((array)$this->methods->all()['others'])[0]);
        $this->assertArrayHasKey('id', (array)((array)$this->methods->all()['others'])[0]);
        $this->assertArrayHasKey('set_at', (array)((array)$this->methods->all()['others'])[0]);

    }
    public function testSameImage() {
        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->assertCount(0, (array)$this->methods->all()['others']);
        $this->assertCount(0, (array)$this->methods->others());
        $img = $img."2";
        $this->methods->set($img);
        $this->assertCount(1, (array)$this->methods->all()['others']);
        $this->assertCount(1, (array)$this->methods->others());
    }
    public function testRemove() {
        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->methods->setRandom();
        $id = $this->methods->all()['current']->id;
        $img = $this->methods->set($img . "1");
        $this->methods->remove($id);
        $this->assertCount(1, $this->methods->others());
        $this->assertEquals($img, $this->methods->current());
        $arr = array_filter($this->methods->others(), function ($item) use($id) {
           return $item->id === $id;
        });
        $this->assertEmpty($arr);
    }
    public function testRemoveAll () {
        $img = "http://lorempicsum.com/random/100";
        $this->methods->set($img);
        $this->methods->setRandom();
        $this->methods->removeAll();
        $this->assertArrayNotHasKey('current', $this->methods->all());
        $this->assertArrayNotHasKey('others', $this->methods->all());
    }
}