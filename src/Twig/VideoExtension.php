<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use RicardoFiorani\Matcher\VideoServiceMatcher;

class VideoExtension extends AbstractExtension
{
    private $youtubeParser;

    public function __construct()
    {
        $this->videoParser = new VideoServiceMatcher();
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('video_thumbnail', [$this, 'videoThumbnail']),
            new TwigFilter('video_player', [$this, 'videoPlayer']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),

        ];
    }

    public function videoThumbnail($value)
    {
        $video = $this->videoParser->parse($value);
        return $video->getSmallThumbnail();
    }

    public function videoPlayer($value)
    {
        $video = $this->videoParser->parse($value);
        return $video->getEmbedCode('100%', 350, true, true);
    }
}
