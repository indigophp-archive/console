<?php

/*
 * This file is part of the Indigo Console package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Console\Command;

/**
 * Auto detects the name of the command
 *
 * Should be used on a Command
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait AutoDetectName
{
    /**
     * After auto-detect the name is cached here
     *
     * @var string
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (is_null($this->name)) {
            $name = explode('\\', __CLASS__);

            // Search for "Command" in namespace segments
            $name = array_slice($name, array_search('Command', $name) + 1);

            // At least one name segment is required
            if (count($name) < 1) {
                throw new LogicException(sprintf('Detecting command name for %s failed', __CLASS__));
            }

            $name = array_map([$this, 'makeUnderscored'], $name);
            $this->name = implode(':', $name);
        }

        return $this->name;
    }

    /**
     * Takes a CamelCased parameter and converts it to lower_case_underscored
     *
     * Borrowed from FuelPHP 1.7 Inflector
     *
     * @param string $name
     *
     * @return string
     */
    private function makeUnderscored($name)
    {
        return strtolower(preg_replace('/([A-Z]+)([A-Z])/', '\1_\2', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', strval($name))));
    }
}
