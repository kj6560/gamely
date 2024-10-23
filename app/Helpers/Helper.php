<?php
function convertUtcToIst($utcTime,$format='d-m-Y H:i:s')
{
    // Create a new DateTime object with the UTC time
    $dateTime = new DateTime($utcTime, new DateTimeZone('UTC'));

    // Set the time zone to IST (Indian Standard Time)
    $dateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));

    // Return the formatted date/time in IST
    return $dateTime->format($format);
}
