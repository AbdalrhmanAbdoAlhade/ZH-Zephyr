<?php

namespace Core;

interface Middleware 
{
    // دالة المعالجة، إذا رجعت true يكمل المسار، إذا رجعت false تقف هنا
    public function handle(): bool;
}