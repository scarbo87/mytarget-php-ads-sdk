<?php

namespace tests\scarbo87\RestApiSdk\Mapper\Type;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Instantiator\InstantiatorInterface;
use scarbo87\RestApiSdk\Mapper\Annotation\Field;
use scarbo87\RestApiSdk\Mapper\Mapper;
use scarbo87\RestApiSdk\Mapper\Type\ObjectType;

class ObjectTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|InstantiatorInterface */
    protected $instantiator;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Reader */
    protected $annotations;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Mapper */
    protected $mapper;

    protected function setUp()
    {
        $this->instantiator = $this->createMock(ObjectInstantiatorMock::class);
        $this->annotations = $this->createMock(Reader::class);
        $this->mapper = $this->createMock(Mapper::class);
    }

    /**
     * @expectedException \scarbo87\RestApiSdk\Mapper\Exception\ClassNotFoundException
     */
    public function testItHydratesAndPanicsIfClassNotFound()
    {
        $objectType = new ObjectType($this->annotations);

        $objectType->hydrated(new \stdClass(), 'Some\Class\Name', $this->mapper);
    }

    public function testItHydrates()
    {
        $objectType = new ObjectType($this->annotations);

        $class = new \ReflectionClass(ObjectStub::class);
        $parentClass = $class->getParentClass();

        $this->annotations->expects($this->exactly(2))
            ->method('getPropertyAnnotation')
            ->withConsecutive(
                [$class->getProperty('integerValue'), Field::class],
                [$parentClass->getProperty('stringValue'), Field::class]
            )
            ->will($this->onConsecutiveCalls(
                new Field(['type' => 'int', 'name' => 'integerValue']),
                new Field(['type' => 'string', 'name' => 'stringValue'])
            ));

        $this->mapper->expects($this->exactly(2))
            ->method('hydrateNew')
            ->withConsecutive(
                ['int', 1],
                ['string', 'A']
            )
            ->will($this->onConsecutiveCalls(
                1,
                'A'
            ));

        /** @var ObjectStub $result */
        $result = $objectType->hydrated(
            [
                'integerValue' => 1,
                'stringValue' => 'A'
            ],
            ObjectStub::class,
            $this->mapper
        );

        $this->assertInstanceOf(ObjectStub::class, $result);

        $this->assertSame(1, $result->getIntegerValue());
        $this->assertSame('A', $result->getStringValue());
    }

    /**
     * @expectedException \scarbo87\RestApiSdk\Mapper\Exception\ContextAwareException
     */
    public function testItHydratesAndRethrowsException()
    {
        $objectType = new ObjectType($this->annotations);

        $class = new \ReflectionClass(ObjectStub::class);

        $this->annotations->expects($this->once())
                          ->method('getPropertyAnnotation')
                          ->with($class->getProperty('integerValue'), Field::class)
                          ->willReturn(new Field(['type' => 'int', 'name' => 'integerValue']));

        $this->mapper->expects($this->once())
                     ->method('hydrateNew')
                     ->willThrowException(new ContextUnawareExceptionStub());

        $objectType->hydrated(
            [
                'integerValue' => 1
            ],
            ObjectStub::class,
            $this->mapper
        );
    }

    public function testItMakesSnapshot()
    {
        $objectType = new ObjectType($this->annotations);

        $class = new \ReflectionClass(ObjectStub::class);
        $parentClass = $class->getParentClass();

        $this->annotations->expects($this->exactly(2))
                          ->method('getPropertyAnnotation')
                          ->withConsecutive(
                              [$class->getProperty('integerValue'), Field::class],
                              [$parentClass->getProperty('stringValue'), Field::class]
                          )
                          ->will($this->onConsecutiveCalls(
                              new Field(['type' => 'int', 'name' => 'integerValue']),
                              new Field(['type' => 'string', 'name' => 'stringValue'])
                          ));

        $this->mapper->expects($this->exactly(2))
                     ->method('snapshot')
                     ->withConsecutive(
                         [2, 'int'],
                         ['B', 'string']
                     )
                     ->will($this->onConsecutiveCalls(
                         2,
                         'B'
                     ));

        $object = new ObjectStub(2, 'B');

        $result = $objectType->snapshot(
            $object,
            ObjectStub::class,
            $this->mapper
        );

        $this->assertSame(
            [
                'integerValue' => 2,
                'stringValue' => 'B'
            ],
            $result
        );
    }

    public function testItReturnsNullIfAllPropertiesAreEmpty()
    {
        $objectType = new ObjectType($this->annotations);

        $object = new ObjectStub(null, null);

        $result = $objectType->snapshot(
            $object,
            ObjectStub::class,
            $this->mapper
        );

        $this->assertSame(null, $result);
    }
}
