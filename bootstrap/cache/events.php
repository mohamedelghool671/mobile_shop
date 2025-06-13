<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\Register' => 
    array (
      0 => 
      array (
        0 => 'App\\Listeners\\Register',
        1 => 'handle',
      ),
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\OrderCreated' => 
    array (
      0 => 'App\\Listeners\\OrderCreated@handle',
    ),
    'App\\Events\\OrderDeliverd' => 
    array (
      0 => 'App\\Listeners\\OrderDeliverd@handle',
    ),
    'App\\Events\\RegisterVerfiyEvent' => 
    array (
      0 => 'App\\Listeners\\RegisterVerfiyListener@handle',
    ),
  ),
);