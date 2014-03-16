<?php

//only one client allowed
$client_arg = array_key_exists(1, $argv) ? $argv[1] : 'wellspring';
define('CLIENT', $client_arg);

require_once('framework/bootstrap.php');

echo "\nbeginning simple script for client " . CLIENT . "...\n\n";
switch (CLIENT) {
    case 'dundermifflin':
        //every BaseModel has an id
        $invention = new Invention(array('id' => 7));
        //dunder mifflin adds a paper used attribute. let's initialize with that.
        $data = array('paper_used' => 18);
        $invention = $invention->initialize($data);

        //the invention plugin adds an attribute of inventors
        $invention->inventors = array('John', 'Paul', 'George', 'Ringo');

        //oops, we forgot a sheet of paper. we can update easily.
        $invention->paper_used = 19;

        //every BaseModel has a save() method, which calls pre_save and post_save hooks.
        if (!$invention->save()) {
            echo "\ninvention failed to save";
        }

        echo "\n--------\n";
        print_r($invention->to_json());
        echo "\n--------\n";
        echo $invention->paperUsed();
        echo "\n";
        break;

    case 'acme':
        $data = array('id'             => 17,
                      'has_explosives' => false,
                      'inventors'      => array('RoadRunner'));
        $invention = new Invention(null, false);
        //we can chain these methods and postpone decoration if we want
        $invention->decorate()->initialize($data);

        echo "\nThere's the roadrunner! Let's try this out...\n";
        echo $invention->ignite();
        echo "\noh right. we need to set our dynamite.\n";
        $invention->has_explosives = true;
        echo $invention->ignite();

        echo "\n--------\n";
        print_r($invention->to_json());
        echo "\n--------\n";
        break;

    case 'wellspring':
        echo "\n\n**** Boring client. Please use either 'acme' or 'dundermifflin'. ****\n";
        echo "eg:\n";
        echo "$ php app.php acme\n\n";
        break;

    default: 
        throw new Exception("Invalid client: '" . CLIENT . "'");
        break;
}
