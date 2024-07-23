<?php
declare(strict_types=1);

namespace ShoplicKr\WpTemplate;

final class Template
{
    /** @var array */
    private static $scopes = [];

    /** @var array */
    private static $context = [];

    /**
     * @param array $scopes Available template paths
     * @param string $templateName Template name to render
     * @param array $context Context variables
     * @param bool $return Return or echo
     *
     * @return string
     */
    public static function template(
        array  $scopes,
        string $templateName,
        array  $context = [],
        bool   $return = false
    ): string
    {
        $output = '';
        $templatePath = '';
        $templateName = trim($templateName, '\\/');

        foreach ($scopes as $scope) {
            $path = "$scope/$templateName.tmpl.php";
            if (file_exists($path) && is_file($path) && is_readable($path)) {
                $templatePath = $path;
                break;
            }
        }

        if ($templatePath) {
            if ($return) {
                ob_start();
            }

            self::$scopes = $scopes;
            self::$context = $context;

            (function ($__wptmpl_template_path__, $__wptmpl_template_context__) {
                if ($__wptmpl_template_context__) {
                    extract($__wptmpl_template_context__, EXTR_SKIP);
                }
                unset($__wptmpl_template_context__);
                include $__wptmpl_template_path__;
            })($templatePath, $context);

            self::$scopes = [];
            self::$context[] = [];

            if ($return) {
                $output = ob_get_clean();
            }
        }

        return $output;
    }

    public static function fragment(string $fragment, bool $useContext = true)
    {
        $found = '';

        foreach (self::$scopes as $scope) {
            $path = "$scope/$fragment.tmpl.php";
            if (file_exists($path) && is_file($path) && is_readable($path)) {
                $found = $path;
                break;
            }
        }

        if ($found) {
            if ($useContext && self::$context) {
                extract(self::$context, EXTR_SKIP);
            }
            include $found;
        }
    }
}
