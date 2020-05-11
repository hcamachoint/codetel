<?php

namespace Telegram\TuriBot;

interface ApiInterface
{
    function Request(string $method, array $data);
}
