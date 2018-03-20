<?php

/* {# inline_template_start #}https://www.youtube.com/watch?v=N1Hs2AQwDgA */
class __TwigTemplate_e7d7dd30b516d0c2e99d19a8e4457af6cbf3a6c76704c2c27e63b6a256b73207 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array();
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array(),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setTemplateFile($this->getTemplateName());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 1
        echo "https://www.youtube.com/watch?v=N1Hs2AQwDgA";
    }

    public function getTemplateName()
    {
        return "{# inline_template_start #}https://www.youtube.com/watch?v=N1Hs2AQwDgA";
    }

    public function getDebugInfo()
    {
        return array (  43 => 1,);
    }

    public function getSource()
    {
        return "{# inline_template_start #}https://www.youtube.com/watch?v=N1Hs2AQwDgA";
    }
}
