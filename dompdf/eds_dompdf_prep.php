<?
function out_number($number, $blank = false)
{
    $number_string = number_format($number, '2', '.', ',');
    
    if ($number == 0 && $blank)
    {
        $number_string = '&nbsp;';
    }
    
    return $number_string;
}

function add_string_spaces($string, $section = 160)
{
    $spaced = "";
    $len = strlen($string);
    
    $pos = 0;
    while ($len > 0)
    {
        $spaced = $spaced . substr($string, $pos, $section) . " ";
        $len -= 90;
        $pos += 90;
    }
    
    return $spaced;
}

function opt_get_value($arr, $key, $default)
{
    return (array_key_exists($key, $arr))? $arr[$key] : $default;
}

function par_place($str_content, $options = array())
{
    $par = '<div style="';
    
    $par .= 'position: absolute; ';
    $par .= 'top: ' . opt_get_value($options, 'top', '0px') . ';';
    $par .= 'left: ' . opt_get_value($options, 'left', '0px') . '; ';
    $par .= 'text-align: ' . opt_get_value($options, 'text-align', 'left') . ';';
    $par .= 'font-size: ' . opt_get_value($options, 'font-size', '10px') . ';';
    $par .= '';
    $par .= '';
    $par .= '';
    $par .= '';
    $par .= '';
    
    $par .= '">';
    
    $par .= $str_content;
    
    $par .= '</div>';
    
    return $par;
}
?>