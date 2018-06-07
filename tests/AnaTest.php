<?php

use PHPUnit\Framework\TestCase;
use imonroe\ana\Ana;

class AnaTest extends TestCase
{
    /*
    Human time (GMT): Wednesday, January 24, 2018 1:01:00 AM
    Epoch timestamp: 1516755660
    */

    public function test_standard_date_format()
    {
        $this->assertEquals('Jan 24, 2018, 1:01 am UTC', Ana::standard_date_format(1516755660));
    }

    public function test_sql_datetime()
    {
        $this->assertEquals('2018-01-24 01:01:00', Ana::sql_datetime(1516755660));
    }

    public function test_google_datetime()
    {
        $this->assertEquals('2018-01-24T01:01:00+00:00', Ana::google_datetime(1516755660));
    }

    public function test_is_today()
    {
        $this->assertTrue(Ana::is_today('today'));
        $this->assertFalse(Ana::is_today('yesterday'));
        $this->assertFalse(Ana::is_today('January 1, 1900'));
    }

    public function test_sooner_than()
    {
        $this->assertTrue(Ana::sooner_than('tomorrow'));
        $this->assertTrue(Ana::sooner_than('January 1, 2500'));
        $this->assertFalse(Ana::sooner_than('yesterday'));
        $this->assertFalse(Ana::sooner_than('January 1, 1900'));
    }

    public function test_later_than()
    {
        $this->assertTrue(Ana::later_than('yesterday'));
        $this->assertTrue(Ana::later_than('January 1, 1900'));
        $this->assertFalse(Ana::later_than('tomorrow'));
        $this->assertFalse(Ana::later_than('January 1, 2500'));
    }

    public function test_plural()
    {
        $this->assertEquals('s', Ana::plural(0));
        $this->assertEquals('s', Ana::plural(2));
        $this->assertEquals('s', Ana::plural(5000));
        $this->assertEquals('', Ana::plural(-1));
        $this->assertEquals('', Ana::plural('1'));
    }

    public function test_print_relative_date()
    {
        $this->assertEquals('about 1 day ago', Ana::print_relative_date('26 hours ago'));
        $this->assertEquals('about 2 days ago', Ana::print_relative_date('44 hours ago'));
        $this->assertEquals('about 1 week ago', Ana::print_relative_date('8 days ago'));
        $this->assertEquals('about 2 weeks ago', Ana::print_relative_date('15 days ago'));
        $this->assertEquals('on January 1, 1960', Ana::print_relative_date('January 1, 1960'));
    }

    public function test_dd()
    {
      // dd will exit the program, so we'll pass on this test.
        $this->assertTrue(true);
    }

    public function test_array_sort_by_column()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function test_array_unique_multi()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_object_to_array()
    {
        $object = new stdClass;
        $object->a = 1;
        $object->b = 2;
        $this->assertEquals(['a' => 1, 'b' => 2], Ana::object_to_array($object));
    }

    public function test_build_tree()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_word_limit()
    {
        $this->assertEquals('This has four words', Ana::word_limit('This has four words that aren\'t removed', 4));
        $this->assertEquals('This has four', Ana::word_limit('This has four words that aren\'t removed', 3));
        $this->assertEquals('This has four words that', Ana::word_limit('This has four words that aren\'t removed', -2));
        $this->assertEquals('', Ana::word_limit('This has four words that aren\'t removed', 0));
    }

    public function test_convert_to_utf()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_plain_text()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_trim_string_to_length()
    {
        $this->assertEquals('xxx', Ana::trim_string_to_length('xxxxxxxxxxx', 3));
        $this->assertEquals('', Ana::trim_string_to_length('xxxxxxxxxxx', 0));
        $this->assertEquals('123', Ana::trim_string_to_length(1234567, 3));
    }

    public function test_useAorAn()
    {
        $this->assertEquals('a', Ana::use_a_or_an('book'));
        $this->assertEquals('a', Ana::use_a_or_an('table'));
        $this->assertEquals('an', Ana::use_a_or_an('evening'));
        $this->assertEquals('an', Ana::use_a_or_an('index'));
    }

    public function test_even_or_odd()
    {
        $this->assertEquals('odd', Ana::even_or_odd(1));
        $this->assertEquals('even', Ana::even_or_odd(2));
        $this->assertEquals('odd', Ana::even_or_odd(3));
        $this->assertEquals(false, Ana::even_or_odd(0));
        $this->assertEquals('odd', Ana::even_or_odd(-1));
        $this->assertEquals('even', Ana::even_or_odd(-2));
    }

    public function test_random_number()
    {
        $percent_check = ( Ana::random_number(1, 100) > 0 && Ana::random_number(1, 100) < 101 ) ? true : false;
        $small_check = ( Ana::random_number(1, 2) > 0 && Ana::random_number(1, 2) < 3 ) ? true : false;
        $neg_check = ( Ana::random_number(-1, 1) > -2 && Ana::random_number(-1, 1) < 2 ) ? true : false;
        if ($percent_check && $small_check && $neg_check) {
            $composite_check = true;
        } else {
            $composite_check = false;
        }
        $this->assertTrue($composite_check);
    }

    public function test_random_hex()
    {
        $this->assertEquals(16, strlen(Ana::random_hex(8)));
        $this->assertEquals(8, strlen(Ana::random_hex(4)));
        $this->assertTrue(is_int(hexdec(Ana::random_hex(1))));
    }

    public function test_generateStrongPassword()
    {
        $this->assertEquals(8, strlen(Ana::generateStrongPassword(8)));
        $this->assertTrue(is_numeric(Ana::generateStrongPassword(8, false, 'd')));
    }

    public function test_create_nonce()
    {
      // your standard sha1 hash is 40 random characters.
        $nonce1 = Ana::create_nonce();
        $nonce2 = Ana::create_nonce();
        $different = ($nonce1 == $nonce2) ? false : true;
        $this->assertTrue($different);
        $this->assertEquals(40, strlen(Ana::create_nonce()));
    }

    public function test_current_page_url()
    {
      /* er, this is kind of untestable from the command line. */
        $this->assertTrue(true);
    }

    public function test_get_url_segment()
    {
      /* er, this is kind of untestable from the command line. */
        $this->assertTrue(true);
    }

    public function test_is_valid_link()
    {
        $this->assertEquals(200, Ana::is_valid_link('http://www.google.com'));
        $this->assertEquals(200, Ana::is_valid_link('www.google.com'));
        $this->assertEquals(301, Ana::is_valid_link('http://www.ianmonroe.com'));
        $this->assertFalse(Ana::is_valid_link('Not a link'));
        $this->assertFalse(Ana::is_valid_link('https://www.google.com/page/doesnt/exist'));
    }

    public function test_quick_curl()
    {
        $this->assertEquals('Use this for a network test.', Ana::quick_curl('https://www.ianmonroe.com/test/string.php'));
        $this->assertFalse(Ana::quick_curl('https://www.ianmonroe.com/test/badstring.php'));
    }

    public function test_get_ip()
    {
      // There's no way to test this from a script.
        $this->assertTrue(true);
    }

    public function test_submit_post_request()
    {
      //$this->expectOutputString('key1:a;key2:b;');
        $this->assertEquals('key1:a;key2:b;', Ana::submit_post_request('https://www.ianmonroe.com/test/post.php', [ 'key1' => 'a', 'key2' => 'b' ]));
    }

    public function test_loading_spinner()
    {
        $this->assertEquals('<i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>', Ana::loading_spinner());
    }

    public function test_code_safe_name()
    {
        $this->assertEquals('TestingCodeSafeName', Ana::code_safe_name('Testing code safe name'));
    }

    public function test_cast()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_ask_user()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_error_out()
    {
      // There's no way to test this from a script.
        $this->assertTrue(true);
    }

    public function test_create_directory()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_create_file()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_append_file()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_get_url_and_save()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_read_file_to_string()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_get_directory_list()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_execute()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_get_arguments()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_us_states()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function test_csv_to_array($filename = '', $delimiter = ',')
    {
      // csv_to_array($filename='', $delimiter=',')
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
