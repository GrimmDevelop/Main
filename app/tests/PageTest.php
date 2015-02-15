<?php

class PageTest extends TestCase {

	/**
	 * test index page redirect
	 *
	 * @return void
	 */
	public function testIndexRedirect()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isRedirect());
	}

	/**
	 * test index page redirect
	 *
	 * @return void
	 */
	public function testSearchRedirect()
	{
		$crawler = $this->client->request('GET', '/search');

		$this->assertTrue($this->client->getResponse()->isOk());
	}

}