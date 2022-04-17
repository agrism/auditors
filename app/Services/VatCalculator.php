<?php


namespace App\Services;


class VatCalculator
{
    const NO               = 'no';
    const YES_21           = 'yes_21';
    const YES_12           = 'yes_12';
    const YES_5            = 'yes_5';
    const YES_21_CAR       = 'yes_21_car';
    const YES_21_PREZ      = 'yes_21_prez';
    const YES_21_REVERS_EU = 'yes_21_revers_EU';

    private $amountWithOutVat = 0.00;
    private $vat              = 0.00;
    private $amountWithVat    = 0.00;
    private $calcName         = self::YES_21;

    private function __construct(
        float $amountWithVat,
        string $calcName = ''
    ) {
        $this->amountWithVat = $amountWithVat;

        $this->calcName = $calcName;
    }

    public static function factory(
        float $amountWitVat,
        string $calcName = self::NO
    ) : self {
        return new self($amountWitVat,
            $calcName);
    }

    public function getCalculator() : array
    {
        return [
            self::NO,
            self::YES_21,
            self::YES_21_CAR,
            self::YES_21_PREZ,
            self::YES_12,
            self::YES_5,
            self::YES_21_REVERS_EU,
        ];
    }

    public function export() : string
    {
        return json_encode([
            'amountWithOutVat' => $this->getAmountWithOutVat(),
            'amountWithVat'    => $this->getAmountWithVat(),
            'vat'              => $this->getVat(),
            'calcName'         => $this->calcName,
        ],
            JSON_PRETTY_PRINT);
    }

    public function getAmountWithOutVat() : float
    {
        return $this->amountWithOutVat;
    }

    public function getAmountWithVat() : float
    {
        return $this->amountWithVat;
    }

    public function getVat() : float
    {
        return $this->vat;
    }

    public function import($data) : self
    {
        $decodedData            = json_decode($data,
            true);
        $this->amountWithVat    = $decodedData['amountWithVat'] ?? 0.00;
        $this->amountWithOutVat = $decodedData['amountWithOutVat'] ?? 0.00;
        $this->vat              = $decodedData['vat'] ?? 0.00;
        $this->calcName         = $decodedData['calcName'] ?? 0.00;

        return $this->calculate();
    }

    public function calculate() : self
    {
        if ($this->calcName === self::NO) {
            $this->vat              = 0.00;
            $this->amountWithOutVat = $this->amountWithVat;

            return $this;
        }

        if ($this->calcName === self::YES_21) {
            $this->vat              = ROUND($this->amountWithVat / 1.21 * 0.21,
                2);
            $this->amountWithOutVat = $this->amountWithVat - $this->vat;

            return $this;
        }

        if ($this->calcName === self::YES_12) {
            $this->vat              = ROUND($this->amountWithVat / 1.12 * 0.12,
                2);
            $this->amountWithOutVat = $this->amountWithVat - $this->vat;

            return $this;
        }

        if ($this->calcName === self::YES_5) {
            $this->vat              = ROUND($this->amountWithVat / 1.05 * 0.05,
                2);
            $this->amountWithOutVat = $this->amountWithVat - $this->vat;

            return $this;
        }

        if ($this->calcName === self::YES_21_CAR) {
            $this->vat              = ROUND($this->amountWithVat / 1.21 * 0.21 * 0.5,
                2);
            $this->amountWithOutVat = $this->amountWithVat - $this->vat;

            return $this;
        }

        if ($this->calcName === self::YES_21_PREZ) {
            $this->vat              = ROUND($this->amountWithVat / 1.21 * 0.21 * 0.4,
                2);
            $this->amountWithOutVat = $this->amountWithVat - $this->vat;

            return $this;
        }

        if ($this->calcName === self::YES_21_REVERS_EU) {
            $this->vat              = ROUND($this->amountWithVat * 0.21,
                2);
            $this->amountWithOutVat = $this->amountWithVat;

            return $this;
        }

        $this->vat              = 0.00;
        $this->amountWithOutVat = $this->amountWithVat;

        return $this;
    }


}