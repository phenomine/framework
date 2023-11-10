<?php

namespace Phenomine\Contracts\Command;


class ArgumentType
{
    /**
     * The argument is mandatory. The command doesn't run if the argument isn't provided.
     */
    public const REQUIRED = 1;

    /**
     * The argument is optional and therefore can be omitted. This is the default behavior of arguments.
     */
    public const OPTIONAL = 2;

    /**
     * The argument can contain any number of values. For that reason, it must be used at the end of the argument list.
     */
    public const ARRAY = 4;
}