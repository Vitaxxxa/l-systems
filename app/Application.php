<?php

class Application extends Silex\Application
{
    use \Silex\Application\TwigTrait;
    use \Silex\Application\UrlGeneratorTrait;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->bootstrapAppConfig($values);
        $this->bootstrapRoutesConfig();
        $this->bootstrapTranslations();
        $this->bootstrapTwig();

    }

    /**
     * @param array $values
     * @throws HttpRuntimeException
     */
    public function bootstrapAppConfig(array $values)
    {
        if (empty($values['config'])) {
            throw new HttpRuntimeException('Application config is not set!');
        }

        $this->register(new \Igorw\Silex\ConfigServiceProvider($values['config']));
    }

    public function bootstrapRoutesConfig()
    {
        $this->register(new \Igorw\Silex\ConfigServiceProvider(__DIR__ . '/configs/routes.php'));
        $this->register(new \MJanssen\Provider\RoutingServiceProvider('config.routes'));
    }

    public function bootstrapTranslations()
    {
        $this->register(new \Silex\Provider\TranslationServiceProvider(), array(
            'locale_fallback' => array('en')
        ));

        $app = $this;

        $this['translator'] = $this->share($this->extend('translator', function ($translator, $app) {
            $translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
            $translator->addResource('yaml', __DIR__.'/Resources/locales/en.yml', 'en');
            $translator->addResource('yaml', __DIR__.'/Resources/locales/ru.yml', 'ru');
            $translator->addResource('yaml', __DIR__.'/Resources/locales/ua.yml', 'ua');


            return $translator;
        }));
    }

    public function bootstrapTwig()
    {
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/Resources/views'
        ));

        $this['twig'] = $this->share($this->extend('twig', function($twig, $this) {
            $app = $this; # save application context in variable

            $twig->addFunction(new Twig_SimpleFunction('asset', function($asset) use($app) {
                return $this['request']->getBaseUrl() . '/assets/' . $asset;
            }));

            return $twig;
        }));
    }
}