<?php
class AppState
{
    public $state = [];
    function __construct()
    {

        $this->state = $_SESSION['state'] ?? [];
        if (!isset($this->state['numbers'])) {
            $this->state['numbers'] = [];
        }
        if (!isset($this->state['ef'])) {
            $this->state['ef'] = [];
        }
    }

    public function __get($name)
    {
        return $this->state[$name] ?? false;
    }

    public function __set($name, $val)
    {
        $this->state[$name] = $val;
        $this->save();
    }
    private function save()
    {
        $_SESSION['state'] = $this->state;
    }
    /**
     * Добавить в список числа
     *
     * @param string $name //
     * @param int $value
     * @return void
     */
    public function addNumber(string $name, int $value)
    {
        if (!isset($this->state['numbers'][$name])) $this->state['numbers'][$name] = [];
        $this->state['numbers'][$name][] = $value;
        $this->save();
    }

    /**
     * Посчитать эффективность
     *
     * @return void
     */
    public function calculate()
    {
        foreach ($this->state['numbers'] as $key => $value) {
            if ($key == 'user') continue;

            if (!isset($this->state['ef'][$key])) $this->state['ef'][$key] = 0;
            if ($this->state['numbers']['user'][count($this->state['numbers']['user']) - 1] == $this->state['numbers'][$key][count($this->state['numbers'][$key]) - 1]) {
                $this->state['ef'][$key]++;
            } else {
                $this->state['ef'][$key]--;
            }
        }
        $this->save();
    }
    /**
     * Разгадать числа
     * @param int $numEx  - количество экстрасенсов
     * @return void
     */
    public function getNumbers($numEx = 2)
    {
        $this->lastNumbers = [];
        for ($i = 0; $i < $numEx; $i++) {
            $number = rand(10, 99);
            $this->lastNumbers['ex' . $i] = $number;
            $this->addNumber('ex' . $i, $number);
        }
    }
    public $lastNumbers = null;
}
