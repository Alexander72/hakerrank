<?php


class Node
{
    /** @var int  */
    private $id;
    /** @var int */
    private $coins;
    /** @var Node|null */
    private $parent;
    /** @var Node[] */
    private $children = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * @param mixed $coins
     */
    public function setCoins($coins): void
    {
        if($coins < 0)
        {
            throw new Exception("$coins < 0");
        }
        $this->coins = $coins;
    }

    /**
     * @return Node|null
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    /**
     * @param Node|null $parent
     */
    public function setParent(?Node $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChildren(Node $node)
    {
        $this->children[$node->getId()] = $node;
    }

    /**
     * @param Node[] $children
     */
    public function setChildren(array $children): void
    {
        foreach($children as $child)
        {
            $this->addChildren($child);
        }
    }

    public function removeChild(Node $node)
    {
        unset($this->children[$node->getId()]);
    }

    public function isRoot(): bool
    {
        return $this->getParent() === null;
    }

    /**
     * @return Node[]
     */
    public function getAllAccessibleNodes(): array
    {
        $result = [];
        if($this->getParent())
        {
            $result = [$this->getParent()->getId() => $this->getParent()];
        }

        return array_merge($result, $this->getChildren());
    }

}

class VisitProcess
{
    private $visited = [];

    public function isVisited(Node $node)
    {
        return isset($this->visited[$node->getId()]);
    }

    public function setVisited(Node $node)
    {
        $this->visited[$node->getId()] = $node;
    }
}

class GameCache
{
    private $cache = [];

    public function save(string $key, bool $value)
    {
        $this->cache[$key] = $value;
    }

    public function get(string $key): ?bool
    {
        return $this->cache[$key] ?? null;
    }

    public function getCacheKey(Graph $graph): string
    {
        return $graph->toString();
    }
}

class GameProcess
{
    private $nodesWithCoins = [];

    public function getNodesWithCoins()
    {
        return $this->nodesWithCoins;
    }

    public function makeTurn(Node $node, int $coins)
    {
        $node->setCoins($node->getCoins() - $coins);
        $node->getParent()->setCoins($node->getParent()->getCoins() + $coins);

        $this->coinsCountChanged($node);
        $this->coinsCountChanged($node->getParent());
    }

    public function revertTurn(Node $node, int $coins)
    {
        $node->setCoins($node->getCoins() + $coins);
        $node->getParent()->setCoins($node->getParent()->getCoins() - $coins);

        $this->coinsCountChanged($node);
        $this->coinsCountChanged($node->getParent());
    }

    private function coinsCountChanged(Node $node)
    {
        if($node->getCoins() == 0)
        {
            unset($this->nodesWithCoins[$node->getId()]);
        }
        else
        {
            $this->addNodeWithCoins($node);
        }
    }

    public function addNodeWithCoins(Node $node)
    {
        if(!$node->isRoot() && $node->getCoins())
        {
            $this->nodesWithCoins[$node->getId()] = $node;
        }
    }
}

class Graph
{
    /** @var Node[] */
    private $nodes;

    public static function build(array $edges, array $coins): self
    {
        $graph = new self();

        foreach($coins as $nodeId => $coin)
        {
            $node = new Node($nodeId);
            $node->setCoins($coin);
            $graph->addNode($node);
        }

        foreach($edges as $edge)
        {
            $graph->addEdge($edge[0], $edge[1]);
        }

        return $graph;
    }

    public function addEdge(int $parentNodeId, int $childNodeId)
    {
        $parentNode = $this->getNode($parentNodeId);
        $childNode = $this->getNode($childNodeId);

        if($parentNode && $childNode)
        {
            $parentNode->addChildren($childNode);
            $childNode->setParent($parentNode);
        }
        else
        {
            $notFoundIds = [];
            if(!$parentNode)
            {
                $notFoundIds[] = $parentNodeId;
            }
            if(!$childNode)
            {
                $notFoundIds[] = $childNodeId;
            }
            throw new Exception('Node with id '.implode(' and ', $notFoundIds).' not found in graph');
        }
    }

    public function addNode(Node $node)
    {
        $this->nodes[$node->getId()] = $node;
    }

    public function getNode(int $nodeId): ?Node
    {
        return $this->nodes[$nodeId] ?? null;
    }

    public function removeEdge(int $parentNodeId, int $childNodeId)
    {
        $parentNode = $this->getNode($parentNodeId);
        $childNode = $this->getNode($childNodeId);

        if($parentNode && $childNode)
        {
            $parentNode->removeChild($childNode);
            $childNode->setParent(null);
        }
    }

    public function isWayExists(int $fromNodeId, int $toNodeId, VisitProcess $visitProcess)
    {
        if($fromNodeId == $toNodeId)
        {
            return true;
        }

        $fromNode = $this->getNode($fromNodeId);
        foreach($fromNode->getAllAccessibleNodes() as $node)
        {
            if(!$visitProcess->isVisited($node))
            {
                $visitProcess->setVisited($node);
                if($this->isWayExists($node->getId(), $toNodeId, $visitProcess))
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function print()
    {
        echo "===================START=====================\n";
        foreach($this->nodes as $node)
        {
            echo "{$node->getId()}({$node->getCoins()}) -> ".($node->getParent() ? $node->getParent()->getId() : '*')."\n";
        }
        echo "====================FINISH====================\n";
    }

    public function toString()
    {
        $result = "";

        foreach($this->nodes as $node)
        {
            $result .= "{$node->getId()}(".(!$node->isRoot() ? $node->getCoins() : '*').")->".(!$node->isRoot() ? $node->getParent()->getId() : '*').";";
        }

        return $result;
    }

    public function isWinning(GameCache $cache, GameProcess $gameProcess, int $deep = 1): bool
    {
        $cacheKey = $cache->getCacheKey($this);

        $cachedValue = $cache->get($cacheKey);
        if($cachedValue === null)
        {
            $nodesWithCoins = $gameProcess->getNodesWithCoins();
            if(!$nodesWithCoins)
            {
                $result = false;
            }
            else
            {
                $result = false;
                foreach ($nodesWithCoins as $node)
                {
                    for ($i = 1; $i <= $node->getCoins(); $i++)
                    {
                        $gameProcess->makeTurn($node, $i);
                        $isWinning = $this->isWinning($cache, $gameProcess, $deep + 1);
                        $gameProcess->revertTurn($node, $i);

                        if (!$isWinning)
                        {
                            $result = true;
                            break 2;
                        }
                    }
                }
            }

            $cache->save($cacheKey, $result);
        }

        return $cache->get($cacheKey);
    }
}

$input = fopen('php://stdin', 'r');

$nodesCount = (int) fgets($input);
$coins = array_map('intval', explode(' ', fgets($input)));
$nodeIdsWithCoins = [];
foreach($coins as $nodeId => $coin)
{
    if($coin)
    {
        $nodeIdsWithCoins[] = $nodeId;
    }
}
$edges = [];
for($i = 0; $i < $nodesCount - 1; $i++)
{
    $edges[] = array_map(function($value){return (int) $value - 1;}, explode(' ', fgets($input)));
}

$cache = new GameCache();

$graph = Graph::build($edges, $coins);
//$graph->print();

$gameProcess = new GameProcess();
foreach($nodeIdsWithCoins as $nodeId)
{
    $gameProcess->addNodeWithCoins($graph->getNode($nodeId));
}

$questionsCount = (int) fgets($input);
for($i = 0; $i < $questionsCount; $i++)
{
    $question = array_map(function($value){return (int) $value - 1;}, explode(' ', fgets($input)));

    $node = $graph->getNode($question[0]);
    $previousParent = $node->getParent();

    $graph->removeEdge($previousParent->getId(), $node->getId());
    $graph->addEdge($question[1], $node->getId());

    //$graph->print();

    if($graph->isWayExists($node->getId(), $previousParent->getId(), new VisitProcess()))
    {
        //echo "Way from {$node->getId()} to {$previousParent->getId()} EXISTS!\n";
        if($graph->isWinning($cache, $gameProcess))
        {
            echo "YES\n";
        }
        else
        {
            echo "NO\n";
        }
    }
    else
    {
        //echo "Way from {$node->getId()} to {$previousParent->getId()} NOT EXISTS!\n";
        echo "INVALID\n";
    }

    $graph->removeEdge($question[1], $node->getId());
    $graph->addEdge($previousParent->getId(), $node->getId());
}
