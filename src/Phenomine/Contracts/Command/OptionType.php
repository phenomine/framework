<?php

namespace Phenomine\Contracts\Command;


class OptionType
{
    /**
     * Do not accept input for the option (e.g. --yell). This is the default behavior of options.
     */
    public const NONE = 1;

    /**
     * A value must be passed when the option is used (e.g. --iterations=5 or -i5).
     */
    public const REQUIRED = 2;

    /**
     * The option may or may not have a value (e.g. --yell or --yell=loud).
     */
    public const OPTIONAL = 4;

    /**
     * The option may have either positive or negative value (e.g. --ansi or --no-ansi).
     */
    public const NEGATABLE = 16;

    /**
     * The option accepts multiple values (e.g. --dir=/foo --dir=/bar).
     */
    public const ARRAY = 8;
}