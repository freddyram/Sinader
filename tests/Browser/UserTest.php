<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @test
     * @return void
     */
    public function test_registrar_emision()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->acceptDialog()
                    ->assertPathIs('/')
                    ->assertSee('Listado de Declaraciones')
                    ->clickLink('Listado de Declaraciones')
                    ->assertPathIs('/send_list')
                    ->assertSee('DECLARACIONES DEL ESTABLECIMIENTO')
                    ->driver->findELement(WebDriverBy::xpath('/html/body/div/div[8]/main/div/div/div/div/div[1]/div[3]/div[4]/a/button')) //BUSCA EL XPATH DEL BOTÓN "REGISTRAR MEDICIONES" VERDE
                    ->click();
            $browser->assertPathIs('/readings')
                    ->assertSee('Fuentes de uso general')
                    ->driver->findELement(WebDriverBy::xpath('/html/body/div/div[25]/main/div/div/div/div/div[2]/div[1]/table/tbody/tr[1]/td[7]/div/button')) //BUSCA EL XPATH DEL BOTÓN "REGISTRAR MEDICIONES" ROJO
                    ->click();
            $browser->whenAvailable('.v-dialog--active > div:nth-child(1)', function ($vcard){ //SELECCIONA EL CSS DEL V-CARD CUANDO ESTÉ DISPONIBLE   
                $vcard->assertSee('Registro de mediciones')
                    ->driver->findELement(WebDriverBy::xpath('/html/body/div/div[24]/div/div/div/section/div[2]/div[3]/div/div/div[1]/div/input')) //BUSCA EL XPATH DEL ELEMENTO LLAMADO "CORRELATIVO MEDICIÓN"
                    ->click();
            })
                    ->type('.v-dialog--active > div:nth-child(1) > div:nth-child(2) > section:nth-child(1) > div:nth-child(2) > div:nth-child(3) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > input:nth-child(2)','Hola') //BUSCA EL CSS DEL INPUT LLAMADO "LABORATORIO" Y ESCRIBE "HOLA"
                    //->driver->findELement(WebDriverBy::xpath('/html/body/div/div[24]/div/div/nav/div/button')) //BUSCA EL XPATH DEL BOTÓN CERRAR
                    ->driver->findELement(WebDriverBy::xpath('/html/body/div/div[24]/div/div/div/section/div[2]/div[5]/button')) //BOTÓN "AGREGAR CORRIDA"
                    ->click();
            $browser->driver->findELement(WebDriverBy::xpath('/html/body/div/div[24]/div/div/nav/div/div[3]/button')) //BOTÓN "GUARDAR"
                    ->click();
            //$browser->waitUntilMissing('.v-dialog--active > div:nth-child(1)')
                    //->keys('.v-dialog--active > div:nth-child(1)', ['{ESCAPE}']);
            $browser->clickLink('Administrar Declaraciones')
                    ->assertSee('Administración de Declaraciones');
        });
    }

    public function test_administrar_declaraciones()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->acceptDialog()
                    ->assertPathIs('/')
                    ->assertSee('Administrar Declaraciones')
                    ->clickLink('Administrar Declaraciones')
                    ->assertPathIs('/send_admin')
                    ->assertSee('Administración de Declaraciones')
                    ->click('.v-select__selections')
                    ->waitFor('.v-menu__content')
                    ->assertSee('Tarapacá')
                    ->elements('.v-menu__content a')[2]
                    ->click();
            $browser->assertSee('Tarapacá')
                    ->driver->findElement(WebDriverBy::xpath('/html/body/div/div[2]/main/div/div/div/div/nav[2]/div/a/button'))
                    ->click();
            $browser->assertPathIs('/')
                    ->assertDontSee('Administración de Declaraciones');
                    //->assertSee('Administración de Declaraciones');
        });
    }

}
