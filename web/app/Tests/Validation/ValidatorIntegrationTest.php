<?php
namespace Acme\Tests;

/**
 * Class ValidatorIntegrationTest
 * @package Acme\Tests
 */
class ValidatorIntegrationTest extends AcmeBaseIntegrationTest {

    /**
     *
     */
    public function testGetRows()
    {
        $req = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $req->expects($this->once())
            ->method('input')
            ->will($this->returnValue(1));

        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$req, $this->response, $this->session])
            ->setMethods(null)
            ->getMock();

        $rows = $validator->getRows('Acme\Models\User', "id");
        $original = get_class($rows);
        $expected = 'Illuminate\Database\Eloquent\Collection';

        $this->assertEquals($original, $expected);

        foreach ($rows as $row) {
            $this->assertEquals($row->id, 1);
        }
    }
}
