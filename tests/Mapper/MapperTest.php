<?php

namespace tests\scarbo87\RestApiSdk\Mapper;

use scarbo87\RestApiSdk\Mapper\Mapper;
use scarbo87\RestApiSdk\Mapper\Type\ArrayType;
use scarbo87\RestApiSdk\Mapper\Type\ScalarType;
use scarbo87\RestApiSdk\Mapper\Type\MixedType;
use scarbo87\RestApiSdk\Mapper\Type\EnumType;
use scarbo87\RestApiSdk\Mapper\Type\ObjectType;
use tests\scarbo87\RestApiSdk\Mapper\Type\EnumTypeMock;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    protected $types;

    public function setUp()
    {
        $this->types = [
            'array' => $this->createMock(ArrayType::class),
            'scalar' => $this->createMock(ScalarType::class),
            'mixed' => $this->createMock(MixedType::class),
            'enum' => $this->createMock(EnumType::class),
            'object' => $this->createMock(ObjectType::class),
        ];
    }

    /**
     * @dataProvider hydratedValues
     * @param $value
     * @param $type
     * @param $hydratedValue
     */
    public function testItHydrates($value, $type, $typeName, $hydratedValue)
    {
        $mapper = new Mapper($this->types);

        /** @var \PHPUnit_Framework_MockObject_MockObject $typeObject */
        $typeObject = $this->types[$type];

        $typeObject->expects($this->once())
            ->method('hydrated')
            ->with($value, $typeName, $mapper)
            ->willReturn($hydratedValue);

        $result = $mapper->hydrateNew($typeName, $value);

        $this->assertSame($hydratedValue, $result);
    }

    /**
     * @expectedException \scarbo87\RestApiSdk\Mapper\Exception\TypeParsingException
     */
    public function testItHydratesAndPanicsIfTypeNotGiven()
    {
        $mapper = new Mapper($this->types);

        $mapper->hydrateNew('', 'someValue');
    }

    /**
     * @dataProvider snapshotValues
     * @param $value
     * @param $type
     * @param $typeName
     * @param $snapshot
     */
    public function testItMakesSnapshot($value, $type, $typeName, $snapshot)
    {
        $mapper = new Mapper($this->types);

        /** @var \PHPUnit_Framework_MockObject_MockObject $typeObject */
        $typeObject = $this->types[$type];

        $typeObject->expects($this->once())
                   ->method('snapshot')
                   ->with($value, $typeName, $mapper)
                   ->willReturn($snapshot);

        $result = $mapper->snapshot($value, $typeName);

        $this->assertSame($snapshot, $result);
    }

    /**
     * @expectedException \scarbo87\RestApiSdk\Mapper\Exception\TypeParsingException
     */
    public function testItMakesSnapshotAndPanicsIfTypeNotGiven()
    {
        $mapper = new Mapper($this->types);

        $mapper->snapshot('someValue', '');
    }

    public function testItMakesSnapshotAndDetectsObjectClass()
    {
        $mapper = new Mapper($this->types);

        /** @var \PHPUnit_Framework_MockObject_MockObject $typeObject */
        $typeObject = $this->types['object'];

        $data = new \stdClass();

        $typeObject->expects($this->once())
                   ->method('snapshot')
                   ->with($data, 'stdClass', $mapper)
                   ->willReturn([]);

        $mapper->snapshot($data);
    }

    public function hydratedValues()
    {
        return [
            [[1, 2], 'array', 'array', [1, 2]],
            [[1, 2], 'array', 'array<int>', [1, 2]],
            [1, 'scalar', 'int', 1],
            [1.3, 'scalar', 'float', 1.3],
            [true, 'scalar', 'bool', true],
            ['Abc', 'scalar', 'string', 'Abc'],
            ['mixed', 'mixed', 'mixed', 'mixed'],
            [777, 'enum', EnumTypeMock::class, EnumTypeMock::fromValue(777)],
            [['integerValue' => 5], 'object', '\stdClass', (object)['integerValue' => 5]]
        ];
    }

    public function snapshotValues()
    {
        return [
            [[1, 2], 'array', 'array', [1, 2]],
            [['A', 'B'], 'array', 'array<string>', ['A', 'B']],
            [1, 'scalar', 'int', 1],
            [1.3, 'scalar', 'float', 1.3],
            [true, 'scalar', 'bool', true],
            ['Abc', 'scalar', 'string', 'Abc'],
            ['mixed', 'mixed', 'mixed', 'mixed'],
            [EnumTypeMock::fromValue(777), 'enum', EnumTypeMock::class, 777],
            [(object)['integerValue' => 5], 'object', '\stdClass', ['integerValue' => 5]]
        ];
    }
}
