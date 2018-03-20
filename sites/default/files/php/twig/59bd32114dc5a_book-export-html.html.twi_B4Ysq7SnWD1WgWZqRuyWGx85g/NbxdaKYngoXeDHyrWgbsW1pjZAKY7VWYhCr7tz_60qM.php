<?php

/* core/themes/stable/templates/layout/book-export-html.html.twig */
class __TwigTemplate_91d39abb42ef1193ec24177a1e936af87909fb7c09d9286073b9b24b27440457 extends Twig_Template
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
        $tags = array("for" => 37);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('for'),
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

        // line 19
        echo "<!DOCTYPE html>
<html";
        // line 20
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["html_attributes"]) ? $context["html_attributes"] : null), "html", null, true));
        echo ">
  <head>
    <title>";
        // line 22
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true));
        echo "</title>
    ";
        // line 23
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "head", array()), "html", null, true));
        echo "
    <base href=\"";
        // line 24
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["base_url"]) ? $context["base_url"] : null), "html", null, true));
        echo "\" />
    <link type=\"text/css\" rel=\"stylesheet\" href=\"misc/print.css\" />
  </head>
  <body>
    ";
        // line 36
        echo "
  ";
        // line 37
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ((isset($context["depth"]) ? $context["depth"] : null) - 1)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            if (((isset($context["depth"]) ? $context["depth"] : null) > 1)) {
                // line 38
                echo "    <div>
  ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo "  ";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["contents"]) ? $context["contents"] : null), "html", null, true));
        echo "
  ";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, ((isset($context["depth"]) ? $context["depth"] : null) - 1)));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            if (((isset($context["depth"]) ? $context["depth"] : null) > 1)) {
                // line 42
                echo "    </div>
  ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 44
        echo "  </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/layout/book-export-html.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  100 => 44,  92 => 42,  87 => 41,  82 => 40,  74 => 38,  69 => 37,  66 => 36,  59 => 24,  55 => 23,  51 => 22,  46 => 20,  43 => 19,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Theme override for printed version of book outline.
 *
 * Available variables:
 * - title: Top level node title.
 * - head: Header tags.
 * - language: Language object.
 * - language_rtl: A flag indicating whether the current display language is a
 *   right to left language.
 * - base_url: URL to the home page.
 * - contents: Nodes within the current outline rendered through
 *   book-node-export-html.html.twig.
 *
 * @see template_preprocess_book_export_html()
 */
#}
<!DOCTYPE html>
<html{{ html_attributes }}>
  <head>
    <title>{{ title }}</title>
    {{ page.head }}
    <base href=\"{{ base_url }}\" />
    <link type=\"text/css\" rel=\"stylesheet\" href=\"misc/print.css\" />
  </head>
  <body>
    {#
      The given node is embedded to its absolute depth in a top level section.
      For example, a child node with depth 2 in the hierarchy is contained in
      (otherwise empty) div elements corresponding to depth 0 and depth 1. This
      is intended to support WYSIWYG output - e.g., level 3 sections always look
      like level 3 sections, no matter their depth relative to the node selected
      to be exported as printer-friendly HTML.
    #}

  {% for i in 1..depth-1 if depth > 1 %}
    <div>
  {% endfor %}
  {{ contents }}
  {% for i in 1..depth-1 if depth > 1 %}
    </div>
  {% endfor %}
  </body>
</html>
";
    }
}
