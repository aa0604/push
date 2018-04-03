<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/3/17
 * Time: 2:19
 */

namespace xing\push\sdk\geTui\igetui;


use xing\push\sdk\geTui\protobuf\PBMessage;

class NotifyInfo extends PBMessage
{
    var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
    public function __construct($reader=null)
    {
        parent::__construct($reader);
        $this->fields['1'] = '\xing\push\sdk\geTui\protobuf\type\PBString';
        $this->values['1'] = '';
        $this->fields['2'] = '\xing\push\sdk\geTui\protobuf\type\PBString';
        $this->values['2'] = '';
        $this->fields['3'] = '\xing\push\sdk\geTui\protobuf\type\PBString';
        $this->values['3'] = '';
    }
    function title()
    {
        return $this->_get_value('1');
    }
    function set_title($value)
    {
        return $this->_set_value('1', $value);
    }
    function content()
    {
        return $this->_get_value('2');
    }
    function set_content($value)
    {
        return $this->_set_value('2', $value);
    }
    function payload()
    {
        return $this->_get_value('3');
    }
    function set_payload($value)
    {
        return $this->_set_value('3', $value);
    }
}