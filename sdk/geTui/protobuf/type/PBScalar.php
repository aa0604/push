<?php
/**
 * @author Nikolai Kordulla
 */

namespace xing\push\sdk\geTui\protobuf\type;

use xing\push\sdk\geTui\protobuf\PBMessage;
class PBScalar extends \xing\push\sdk\geTui\protobuf\PBMessage
{
	/**
	 * Set scalar value
	 */
	public function set_value($value)
	{	
		$this->value = $value;	
	}

	/**
	 * Get the scalar value
	 */
	public function get_value()
	{
		return $this->value;
	}
}
?>
