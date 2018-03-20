<?php

/* core/themes/stable/templates/navigation/book-navigation.html.twig */
class __TwigTemplate_fd2e110c3624403e4373b4d1209efe0631789824560afb5feacb2cefd4e76ff0 extends Twig_Template
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
        $tags = array("if" => 31);
        $filters = array("t" => 35);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array('t'),
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

        // line 31
        if (((isset($context["tree"]) ? $context["tree"] : null) || (isset($context["has_links"]) ? $context["has_links"] : null))) {
            // line 32
            echo "  <nav role=\"navigation\" aria-labelledby=\"book-label-";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["book_id"]) ? $context["book_id"] : null), "html", null, true));
            echo "\">
    ";
            // line 33
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["tree"]) ? $context["tree"] : null), "html", null, true));
            echo "
    ";
            // line 34
            if ((isset($context["has_links"]) ? $context["has_links"] : null)) {
                // line 35
                echo "      <h2>";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Book traversal links for")));
                echo " ";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["book_title"]) ? $context["book_title"] : null), "html", null, true));
                echo "</h2>
      <ul>
      ";
                // line 37
                if ((isset($context["prev_url"]) ? $context["prev_url"] : null)) {
                    // line 38
                    echo "        <li>
          <a href=\"";
                    // line 39
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["prev_url"]) ? $context["prev_url"] : null), "html", null, true));
                    echo "\" rel=\"prev\" title=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Go to previous page")));
                    echo "\"><b>";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("‹")));
                    echo "</b> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["prev_title"]) ? $context["prev_title"] : null), "html", null, true));
                    echo "</a>
        </li>
      ";
                }
                // line 42
                echo "      ";
                if ((isset($context["parent_url"]) ? $context["parent_url"] : null)) {
                    // line 43
                    echo "        <li>
          <a href=\"";
                    // line 44
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["parent_url"]) ? $context["parent_url"] : null), "html", null, true));
                    echo "\" title=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Go to parent page")));
                    echo "\">";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Up")));
                    echo "</a>
        </li>
      ";
                }
                // line 47
                echo "      ";
                if ((isset($context["next_url"]) ? $context["next_url"] : null)) {
                    // line 48
                    echo "        <li>
          <a href=\"";
                    // line 49
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["next_url"]) ? $context["next_url"] : null), "html", null, true));
                    echo "\" rel=\"next\" title=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Go to next page")));
                    echo "\">";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["next_title"]) ? $context["next_title"] : null), "html", null, true));
                    echo " <b>";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("›")));
                    echo "</b></a>
        </li>
      ";
                }
                // line 52
                echo "    </ul>
    ";
            }
            // line 54
            echo "  </nav>
";
        }
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/navigation/book-navigation.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 54,  115 => 52,  103 => 49,  100 => 48,  97 => 47,  87 => 44,  84 => 43,  81 => 42,  69 => 39,  66 => 38,  64 => 37,  56 => 35,  54 => 34,  50 => 33,  45 => 32,  43 => 31,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Theme override to navigate books.
 *
 * Presented under nodes that are a part of book outlines.
 *
 * Available variables:
 * - tree: The immediate children of the current node rendered as an unordered
 *   list.
 * - current_depth: Depth of the current node within the book outline. Provided
 *   for context.
 * - prev_url: URL to the previous node.
 * - prev_title: Title of the previous node.
 * - parent_url: URL to the parent node.
 * - parent_title: Title of the parent node. Not printed by default. Provided
 *   as an option.
 * - next_url: URL to the next node.
 * - next_title: Title of the next node.
 * - has_links: Flags TRUE whenever the previous, parent or next data has a
 *   value.
 * - book_id: The book ID of the current outline being viewed. Same as the node
 *   ID containing the entire outline. Provided for context.
 * - book_url: The book/node URL of the current outline being viewed. Provided
 *   as an option. Not used by default.
 * - book_title: The book/node title of the current outline being viewed.
 *
 * @see template_preprocess_book_navigation()
 */
#}
{% if tree or has_links %}
  <nav role=\"navigation\" aria-labelledby=\"book-label-{{ book_id }}\">
    {{ tree }}
    {% if has_links %}
      <h2>{{ 'Book traversal links for'|t }} {{ book_title }}</h2>
      <ul>
      {% if prev_url %}
        <li>
          <a href=\"{{ prev_url }}\" rel=\"prev\" title=\"{{ 'Go to previous page'|t }}\"><b>{{ '‹'|t }}</b> {{ prev_title }}</a>
        </li>
      {% endif %}
      {% if parent_url %}
        <li>
          <a href=\"{{ parent_url }}\" title=\"{{ 'Go to parent page'|t }}\">{{ 'Up'|t }}</a>
        </li>
      {% endif %}
      {% if next_url %}
        <li>
          <a href=\"{{ next_url }}\" rel=\"next\" title=\"{{ 'Go to next page'|t }}\">{{ next_title }} <b>{{ '›'|t }}</b></a>
        </li>
      {% endif %}
    </ul>
    {% endif %}
  </nav>
{% endif %}
";
    }
}
