<?php

declare (strict_types=1);
namespace ECSPrefix202209\Symplify\RuleDocGenerator\Contract\Category;

use ECSPrefix202209\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
interface CategoryInfererInterface
{
    public function infer(RuleDefinition $ruleDefinition) : ?string;
}
