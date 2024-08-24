<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    const TYPES = [
        ['id' => 'default', 'label' => 'Default'],
        ['id' => 'show_on_load', 'label' => 'Show on Page Load'],
        ['id' => 'show_before_closing', 'label' => 'Show before page closing'],
        ['id' => 'show_after_sometime', 'label' => 'Show after sometime'],
    ];

    const ANIMATION_OUT = [
        ['id' => 'animate__backOutDown', 'label' => 'Back Out Down'],
        ['id' => 'animate__backOutLeft', 'label' => 'Back Out Left'],
        ['id' => 'animate__backOutRight', 'label' => 'Back Out Right'],
        ['id' => 'animate__backOutUp', 'label' => 'Back Out Up'],
        ['id' => 'animate__bounceOut', 'label' => 'Bounce Out'],
        ['id' => 'animate__bounceOutDown', 'label' => 'Bounce Out Down'],
        ['id' => 'animate__bounceOutLeft', 'label' => 'Bounce Out Left'],
        ['id' => 'animate__bounceOutRight', 'label' => 'Bounce Out Right'],
        ['id' => 'animate__bounceOutUp', 'label' => 'Bounce Out Up'],
        ['id' => 'animate__flipOutX', 'label' => 'Flip Out X'],
        ['id' => 'animate__flipOutY', 'label' => 'Flip Out Y'],
        ['id' => 'animate__rotateOut', 'label' => 'Rotate Out'],
        ['id' => 'animate__rotateOutDownLeft', 'label' => 'Rotate Out Down Left'],
        ['id' => 'animate__rotateOutDownRight', 'label' => 'Rotate Out Down Right'],
        ['id' => 'animate__rotateOutUpLeft', 'label' => 'Rotate Out Up Left'],
        ['id' => 'animate__rotateOutUpRight', 'label' => 'Rotate Out Up Right'],
        ['id' => 'animate__hinge', 'label' => 'Hinge'],
        ['id' => 'animate__rollOut', 'label' => 'Roll Out'],
        ['id' => 'animate__zoomOut', 'label' => 'Zoom Out'],
        ['id' => 'animate__zoomOutDown', 'label' => 'Zoom Out Down'],
        ['id' => 'animate__zoomOutLeft', 'label' => 'Zoom Out Left'],
        ['id' => 'animate__zoomOutRight', 'label' => 'Zoom Out Right'],
        ['id' => 'animate__zoomOutUp', 'label' => 'Zoom Out Up'],
        ['id' => 'animate__slideOutDown', 'label' => 'Slide Out Down'],
        ['id' => 'animate__slideOutLeft', 'label' => 'Slide Out Left'],
        ['id' => 'animate__slideOutRight', 'label' => 'Slide Out Right'],
        ['id' => 'animate__slideOutUp', 'label' => 'Slide Out Up']
    ];

    const ANIMATION_IN = [
        ['id' => 'animate__bounce', 'label' => 'Bounce'],
        ['id' => 'animate__flash', 'label' => 'Flash'],
        ['id' => 'animate__pulse', 'label' => 'Pulse'],
        ['id' => 'animate__rubberBand', 'label' => 'Rubber Band'],
        ['id' => 'animate__shakeX', 'label' => 'Shake X'],
        ['id' => 'animate__shakeY', 'label' => 'Shake Y'],
        ['id' => 'animate__headShake', 'label' => 'Headshake'],
        ['id' => 'animate__swing', 'label' => 'Swing'],
        ['id' => 'animate__tada', 'label' => 'Tada'],
        ['id' => 'animate__wobble', 'label' => 'Wobble'],
        ['id' => 'animate__jello', 'label' => 'Jello'],
        ['id' => 'animate__heartBeat', 'label' => 'Heartbeat'],
        ['id' => 'animate__backInDown', 'label' => 'Back In Down'],
        ['id' => 'animate__backInLeft', 'label' => 'Back In Left'],
        ['id' => 'animate__backInRight', 'label' => 'Back In Right'],
        ['id' => 'animate__backInUp', 'label' => 'Back In Up'],
        ['id' => 'animate__bounceIn', 'label' => 'Bounce In'],
        ['id' => 'animate__bounceInDown', 'label' => 'Bounce In Down'],
        ['id' => 'animate__bounceInLeft', 'label' => 'Bounce In Left'],
        ['id' => 'animate__bounceInRight', 'label' => 'Bounce In Right'],
        ['id' => 'animate__bounceInUp', 'label' => 'Bounce In Up'],
        ['id' => 'animate__fadeIn', 'label' => 'Fade In'],
        ['id' => 'animate__fadeInDown', 'label' => 'Fade In Down'],
        ['id' => 'animate__fadeInDownBig', 'label' => 'Fade In Down Big'],
        ['id' => 'animate__fadeInLeft', 'label' => 'Fade In Left'],
        ['id' => 'animate__fadeInLeftBig', 'label' => 'Fade In Left Big'],
        ['id' => 'animate__fadeInRight', 'label' => 'Fade In Right'],
        ['id' => 'animate__fadeInRightBig', 'label' => 'Fade In Right Big'],
        ['id' => 'animate__fadeInUp', 'label' => 'Fade In Up'],
        ['id' => 'animate__fadeInUpBig', 'label' => 'Fade In Up Big'],
        ['id' => 'animate__fadeInTopLeft', 'label' => 'Fade In Top Left'],
        ['id' => 'animate__fadeInTopRight', 'label' => 'Fade In Top Right'],
        ['id' => 'animate__fadeInBottomLeft', 'label' => 'Fade In Bottom Left'],
        ['id' => 'animate__fadeInBottomRight', 'label' => 'Fade In Bottom Right'],
        ['id' => 'animate__flip', 'label' => 'Flip'],
        ['id' => 'animate__flipInX', 'label' => 'Flip In X'],
        ['id' => 'animate__flipInY', 'label' => 'Flip In Y'],
        ['id' => 'animate__lightSpeedInRight', 'label' => 'Light Speed In Right'],
        ['id' => 'animate__lightSpeedInLeft', 'label' => 'Light Speed In Left'],
        ['id' => 'animate__lightSpeedOutRight', 'label' => 'Light Speed Out Right'],
        ['id' => 'animate__lightSpeedOutLeft', 'label' => 'Light Speed Out Left'],
        ['id' => 'animate__rotateIn', 'label' => 'Rotate In'],
        ['id' => 'animate__rotateInDownLeft', 'label' => 'Rotate In Down Left'],
        ['id' => 'animate__rotateInDownRight', 'label' => 'Rotate In Down Right'],
        ['id' => 'animate__rotateInUpLeft', 'label' => 'Rotate In Up Left'],
        ['id' => 'animate__rotateInUpRight', 'label' => 'Rotate In Up Right'],
        ['id' => 'animate__jackInTheBox', 'label' => 'Jack In The Box'],
        ['id' => 'animate__rollIn', 'label' => 'Roll In'],
        ['id' => 'animate__zoomIn', 'label' => 'Zoom In'],
        ['id' => 'animate__zoomInDown', 'label' => 'Zoom In Down'],
        ['id' => 'animate__zoomInLeft', 'label' => 'Zoom In Left'],
        ['id' => 'animate__zoomInRight', 'label' => 'Zoom In Right'],
        ['id' => 'animate__zoomInUp', 'label' => 'Zoom In Up'],
        ['id' => 'animate__slideInDown', 'label' => 'Slide In Down'],
        ['id' => 'animate__slideInLeft', 'label' => 'Slide In Left'],
        ['id' => 'animate__slideInRight', 'label' => 'Slide In Right'],
        ['id' => 'animate__slideInUp', 'label' => 'Slide In Up']
    ];

    protected $table = 'popup';

    protected $fillable = ['name'];
}
