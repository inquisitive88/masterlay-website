<?php
/**
 * Shop — branded email wrapper, matching the website's house email style
 * (dark header with orange title, white content card, dark footer with the
 * company signature block).
 */

function shop_branded_email(string $title, string $subtitle, string $bodyHtml): string
{
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Masterlay Renovations Inc.';
    $address = defined('ADDRESS') ? ADDRESS : 'Brampton, ON';
    $phone = defined('PHONE') ? PHONE : '(647) 846-5449';
    $email = defined('EMAIL') ? EMAIL : 'info@masterlayrenovations.ca';

    return "
    <div style='font-family: Arial, Helvetica, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 0;'>
        <div style='background-color: #0A0A0A; padding: 30px 40px; text-align: center;'>
            <h1 style='color: #FAA416; margin: 0; font-size: 24px;'>" . htmlspecialchars($title) . "</h1>
            " . ($subtitle !== '' ? "<p style='color: #b9b9b9; margin: 8px 0 0; font-size: 14px;'>" . htmlspecialchars($subtitle) . "</p>" : '') . "
        </div>
        <div style='background-color: #ffffff; padding: 30px 40px; color: #333; font-size: 14px; line-height: 1.6;'>
            {$bodyHtml}
        </div>
        <div style='background-color: #0A0A0A; padding: 24px 40px; text-align: center;'>
            <p style='color: #FAA416; margin: 0 0 6px; font-size: 14px; font-weight: bold;'>{$siteName}</p>
            <p style='color: #b9b9b9; margin: 0; font-size: 12px;'>Custom Woodworking &bull; Flooring &bull; Stairs &bull; Renovations</p>
            <p style='color: #b9b9b9; margin: 6px 0 0; font-size: 12px;'>
                {$address} &bull; <a href='tel:{$phone}' style='color: #FAA416; text-decoration: none;'>{$phone}</a>
                &bull; <a href='mailto:{$email}' style='color: #FAA416; text-decoration: none;'>{$email}</a>
            </p>
            <p style='color: #8a8a8a; margin: 10px 0 0; font-size: 11px;'>&copy; " . date('Y') . " {$siteName} &bull; masterlayrenovations.ca</p>
        </div>
    </div>";
}
