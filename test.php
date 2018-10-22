<?php
class MyClass {
public static $_function = null;

public static function first_function() {

    $f = function () {
            // some stuff here  
			self::second_function();
    };
	static::$_function = $f;

} // End first_function

private static function second_function() { 
echo 2;
    // do stuff

} // End second_function

	public function do () {
		$reflect = new ReflectionFunction(static::$_function);
        //$args    = $this->bindParams($reflect, $vars);

        return $reflect->invoke();
	}

}
$c = new MyClass();
$function = MyClass::first_function();
$c->do();
   