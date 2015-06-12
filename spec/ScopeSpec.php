<?php

namespace spec\Slade;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScopeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Slade\Scope');
    }

    function it_can_retrieve_a_nested_array_item()
    {
        $this->beConstructedWith(['one' => ['two' => 'three']]);

        $this['one.two']->shouldReturn('three');
    }

    function it_can_retrieve_a_nested_object_property()
    {
        $this->beConstructedWith(['c' => new C]);

        $this['c.d.b']->shouldReturn('hi');
    }

    function it_can_retrieve_a_mix_of_nested_arrays_and_nested_object_properties()
    {
        $this->beConstructedWith(['one' => ['two' => new C]]);

        $this['one.two.d.b']->shouldReturn('hi');
    }

    function it_can_set_a_nested_array_item()
    {
        $this['one.two'] = 'three';

        $this['one']->shouldReturn(['two' => 'three']);
    }

    function it_can_set_a_nested_object_item()
    {
        $this->beConstructedWith(['one' => ['two' => new C]]);

        $this['one.two.d.e.f'] = 'bye';

        $this['one.two.d']->shouldHaveType('spec\Slade\A');
        $this['one.two.d.e']->shouldReturn(['f' => 'bye']);
    }
}

class A {
    public $b = 'hi';
    public $e = [];
}

class C {
    public $d;

    public function __construct()
    {
        $this->d = new A;
    }
}
