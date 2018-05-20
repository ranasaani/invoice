<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Renderers;

interface IRenderer {

	const ASSETS_PATH = __DIR__ . '/../../assets/';

	public function x(): int;

	public function y(): int;

	public function textWidth(string $text, ?callable $setCallback = null): float;

	public function width(): float;

	public function height(): float;

	public function createNew(): void;

	public function addPage(): void;

	public function addFont(string $family, string $file, string $fontStyle = Settings::FONT_STYLE_NONE): void;

	public function rect(float $x, float $y, float $width, float $height, ?callable $setCallback = null): void;

	public function polygon(array $points, ?callable $setCallback = null): void;

	public function cell(float $x, float $y, float $width, ?float $height, ?string $text, ?callable $setCallback = null): void;

	public function output(): string;

}
