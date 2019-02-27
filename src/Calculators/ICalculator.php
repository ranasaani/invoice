<?php declare(strict_types = 1);

namespace Contributte\Invoice\Calculators;

interface ICalculator
{

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return mixed
	 */
	public function add($op1, $op2);

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return mixed
	 */
	public function mul($op1, $op2);

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return mixed
	 */
	public function div($op1, $op2);

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return mixed
	 */
	public function sub($op1, $op2);

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 */
	public function comp($op1, $op2): int;

}
