<?php

/**
 * 站点元信息管理模块
 * 提供站点基础配置、关键词管理及描述文本生成功能
 */

class SiteMeta
{
    /**
     * @var array 站点核心配置
     */
    private array $config;

    /**
     * @var array 关键词列表
     */
    private array $keywords;

    /**
     * @var array 站点描述模板
     */
    private array $descriptionTemplates;

    /**
     * 构造函数
     * @param array $config 站点配置
     * @param array $keywords 关键词
     * @param array $templates 描述模板
     */
    public function __construct(
        array $config = [],
        array $keywords = [],
        array $templates = []
    ) {
        $this->config = $config ?: [
            'site_name' => 'Portal Fishing Master',
            'site_url' => 'https://portal-fishingmaster.com',
            'site_description' => '专业的捕鱼达人游戏门户',
            'language' => 'zh-CN',
            'charset' => 'UTF-8'
        ];

        $this->keywords = $keywords ?: [
            '捕鱼达人',
            '捕鱼游戏',
            '街机捕鱼',
            '捕鱼达人游戏',
            'online fishing game'
        ];

        $this->descriptionTemplates = $templates ?: [
            '{site_name} - 最新{keyword}游戏推荐与攻略',
            '{keyword}爱好者聚集地 | {site_name}',
            '探索{keyword}的无限乐趣 - {site_name}',
            '{site_name}: 您身边的{keyword}专家平台'
        ];
    }

    /**
     * 获取站点元信息数组
     * @return array
     */
    public function getMetaArray(): array
    {
        return [
            'title' => $this->config['site_name'] . ' - ' . $this->getFirstKeyword(),
            'description' => $this->generateDescription(),
            'keywords' => implode(', ', $this->keywords),
            'url' => $this->config['site_url'],
            'language' => $this->config['language']
        ];
    }

    /**
     * 生成简短描述文本
     * @param int $maxLength 最大长度
     * @return string
     */
    public function generateDescription(int $maxLength = 150): string
    {
        $template = $this->descriptionTemplates[array_rand($this->descriptionTemplates)];
        $keyword = $this->keywords[array_rand($this->keywords)];
        
        $description = str_replace(
            ['{site_name}', '{keyword}'],
            [$this->config['site_name'], $keyword],
            $template
        );

        if (mb_strlen($description) > $maxLength) {
            $description = mb_substr($description, 0, $maxLength - 3) . '...';
        }

        return $description;
    }

    /**
     * 获取第一个关键词
     * @return string
     */
    private function getFirstKeyword(): string
    {
        return $this->keywords[0] ?? '捕鱼达人';
    }

    /**
     * 生成HTML meta标签
     * @return string
     */
    public function generateMetaTags(): string
    {
        $meta = $this->getMetaArray();
        $tags = '';
        
        $tags .= '<meta charset="' . $this->escapeHtml($this->config['charset']) . '">' . PHP_EOL;
        $tags .= '<title>' . $this->escapeHtml($meta['title']) . '</title>' . PHP_EOL;
        $tags .= '<meta name="description" content="' . $this->escapeHtml($meta['description']) . '">' . PHP_EOL;
        $tags .= '<meta name="keywords" content="' . $this->escapeHtml($meta['keywords']) . '">' . PHP_EOL;
        $tags .= '<link rel="canonical" href="' . $this->escapeHtml($meta['url']) . '">' . PHP_EOL;
        
        return $tags;
    }

    /**
     * HTML转义
     * @param string $input
     * @return string
     */
    private function escapeHtml(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }

    /**
     * 获取站点配置
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 获取关键词列表
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * 添加关键词
     * @param string $keyword
     * @return bool
     */
    public function addKeyword(string $keyword): bool
    {
        if (!in_array($keyword, $this->keywords, true)) {
            $this->keywords[] = $keyword;
            return true;
        }
        return false;
    }

    /**
     * 更新站点描述
     * @param string $description
     * @return void
     */
    public function updateSiteDescription(string $description): void
    {
        $this->config['site_description'] = $description;
    }
}

// 使用示例
$siteMeta = new SiteMeta();
$siteMeta->addKeyword('深海捕鱼');
$description = $siteMeta->generateDescription();

echo '站点描述: ' . $description . PHP_EOL;
echo PHP_EOL . '生成的Meta标签:' . PHP_EOL;
echo $siteMeta->generateMetaTags();