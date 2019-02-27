<?php declare(strict_types = 1);

namespace WebChemistry\Invoice\Calculators;

class FloatCalculator implements ICalculator {

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return float|int
	 */
	public function add($op1, $op2) {
		return $op1 + $op2;
	}

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return float|int
	 */
	public function mul($op1, $op2) {
		return $op1 * $op2;
	}

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return float|int
	 */
	public function div($op1, $op2) {
		return $op1 / $op2;
	}

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return float|int
	 */
	public function sub($op1, $op2) {
		return $op1 - $op2;
	}

	/**
	 * @param string|int|float $op1
	 * @param string|int|float $op2
	 * @return int
	 */
	public function comp($op1, $op2): int {
		return $op1 <=> $op2;
	}

}
