<?php

namespace Core;

class DotEnv 
{
    public static function load(string $path): void 
    {
        $file = $path . '/.env';
        
        if (!file_exists($file)) {
            return; // لو الملف مش موجود متعملش حاجة (أو ارجع بـ Exception حسب رغبتك)
        }

        // قراءة الملف كـ أسطر مع تجاهل الأسطر الفاضية
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // تجاهل التعليقات التي تبدأ بـ #
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // تقسيم السطر عند أول علامة =
            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);

            // إزالة علامات الاقتباس إن وجدت " أو '
            $value = trim($value, "\"'");

            // تعيين القيمة في السستم
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}