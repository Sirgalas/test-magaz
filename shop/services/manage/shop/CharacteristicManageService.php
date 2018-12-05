<?php
namespace shop\services\manage\Shop;
use shop\entities\Shop\Characteristic;
use shop\forms\manage\Shop\CharacteristicForm;
use shop\repositories\shop\CharacteristicRepository;
class CharacteristicManageService
{
    private $characteristics;
    public function __construct(CharacteristicRepository $characteristics)
    {
        $this->characteristics = $characteristics;
    }
    public function create(CharacteristicForm $form): Characteristic
    {
        $characteristic = Characteristic::create($form,$form->variants);
        $this->characteristics->save($characteristic);
        return $characteristic;
    }
    public function edit($id, CharacteristicForm $form): void
    {
        $characteristic = $this->characteristics->get($id);
        $characteristic->edit($form,$form->variants);
        $this->characteristics->save($characteristic);
    }
    public function remove($id): void
    {
        $characteristic = $this->characteristics->get($id);
        $this->characteristics->remove($characteristic);
    }
}
