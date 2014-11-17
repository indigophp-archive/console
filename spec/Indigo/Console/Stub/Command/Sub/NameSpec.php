<?php

namespace spec\Indigo\Console\Stub\Command\Sub;

use PhpSpec\ObjectBehavior;

class NameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Console\Stub\Command\Sub\Name');
        $this->shouldUseTrait('Indigo\Console\Command\AutoDetectName');
        $this->shouldImplement('Indigo\Console\Command');
    }

    public function it_should_auto_detect_its_name()
    {
        $this->getName()->shouldReturn('sub:name');
    }

    function getMatchers()
    {
        return [
            'useTrait' => function ($subject, $trait) {
                return class_uses($subject, $trait);
            }
        ];
    }
}
