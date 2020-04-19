<?php
namespace carlonicora\minimalism\services\jsonapi\traits;

trait linksTrait {
    /** @var array|null  */
    protected ?array $links=null;

    /**
     * @return bool
     */
    protected function hasLinks() : bool {
        return !empty($this->links);
    }

    /**
     * @param string $key
     * @param string $url
     * @param array|null $meta
     */
    public function addLink(string $key, string $url, array $meta = null): void {
        if ($this->links === null){
            $this->links = [];
        }

        if (empty($meta)) {
            $this->links[$key] = $url;
        } else {
            $this->links[$key] = [
                'href' => $url,
                'meta' => $meta
            ];
        }
    }

    /**
     * @param array $links
     */
    public function addLinks(array $links): void {
        if ($this->links === null){
            $this->links = [];
        }

        $this->links = array_merge($this->links, $links);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getLink(string $name): ?string {
        if ($this->links === null || !array_key_exists($name, $this->links)){
            return null;
        }

        return $this->links[$name];
    }
}