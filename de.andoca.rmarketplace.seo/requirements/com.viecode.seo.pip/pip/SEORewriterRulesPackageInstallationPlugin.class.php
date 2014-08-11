<?php

//wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

//onw imports
//none

/**
 * Configs the SEO for an eay and direct
 * use. It also uninstalls all old remains
 * from your SEO-config.
 * 
 * @author		BBLL
 * @copyright	2005 - 2009
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		de.wbb-security.seo.pip
 * @subpackage	acp.package.plugin
 */
class SEORewriterRulesPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin
{
	public $tagName = "seorewriterrules";
	
	protected $seoXmlTree = array();
	protected $ownRewriterRules = array();
	protected $newRewriterRules = array();
	protected $rewriterRules = "";
	
	/**
	 * @see PackageInstallationPlugin::install()
	 */
	public function install()
	{
		parent::install();
		
		// Create instance
		if(!$xml = $this->getXML())
		{
			return;
		}
		
		// Save XML-Tree
		$this->seoXmlTree = $xml->getElementTree("data");
		
		// Get new Rewriter Rules
		$this->newRewriterRules = $this->getNewSEORewriterRules();
		
		// Get own Rewriter Rules
		$this->ownRewriterRules = $this->getOwnRewriterRules();
		
		// Check is unique
		/*if(!$this->isUnique($this->ownRewriterRules))
		{
			throw new SystemException("Dupublicate entry for a seo plugin in *.htacess-file. Please check your *.htaccess-file!");
		}*/
		
		// Define all rewriter rules
		$this->rewriterRules = $this->addRulesToCustomRules($this->ownRewriterRules, $this->newRewriterRules);
		
		// Save new Rules
		$this->saveNewSEORewriterRules($this->rewriterRules);
		
		// Rebuild *.htaccess-file
		$this->rebuildHtaccessFile();
	}
	
	/**
	 * Gets all RewriterRules of the given XML
	 * 
	 * @access	protected
	 * 
	 * @return	void
	 */
	protected function getNewSEORewriterRules()
	{
		// Rewriter rules
		$rewriterRules = array();
		
		// Is empty xml
		if(count($this->seoXmlTree))
		{
			//Handle items
			foreach($this->seoXmlTree['children'] as $key => $action)
			{
				// Count items in actions
				if(count($action))
				{
					// Handle action (import)
					if($action['name'] == "import")
					{	
						// Extract rules
						foreach($action['children'] as $import)
						{	
							foreach($import['children'] as $child)
							{
								// Get items
								if($child['name'] == "rewriterRule")
								{
									// Get comments
									if(isset($child['attrs']['comment']) AND !empty($child['attrs']['comment']))
									{
										$rewriterRules[] = "# ".$child['attrs']['comment'];
									}
									else
									{
										$child['attrs']['comment'] = "";
									}
									
									// Check Rule Content
									if(empty($child['cdata']))
									{
										throw new SystemException("There is an empty rewriter rule");
									}
									
									// Get Rewriter Rule
									if($child['cdata'] != $child['attrs']['comment'])
									{
										$rewriterRules[] = $child['cdata'];
									}
								}
							}
						}
					}
				}
			}
		}
		
		// Return
		return $rewriterRules;
	}
	
	/**
	 * Rebuilds the htaccess file if a WBB is installed
	 * 
	 * @access	protected
	 * 
	 * @return	void
	 */
	protected function rebuildHtaccessFile()
	{
		// Build new .htaccess-file
		if(defined("WBB_DIR"))
		{
			// Load wbb-seo-util
			require_once(WBB_DIR.'lib/data/page/seo/WBBSEOUtil.class.php');
								
			// Create new File
			WBBSEOUtil::rebuildConfigFile();
		}
		
       	// Call hasUpdate event
		EventHandler::fireAction($this, 'rebuildHtaccessFile');
	}
	
	/**
	 * Checks whether a Package has already defined
	 * this rules
	 * 
	 * @access	protected
	 * 
	 * @param	array		$ownSEORewriterRules
	 * 
	 * @return	boolean
	 */
	protected function isUnique($ownSEORewriterRules)
	{
		if($this->hasUninstall())
		{
			return false;
		}
		
		if(in_array("## start seo-rules (".$this->installation->getPackageID().") | Do not remove this and the following lines ##", $ownSEORewriterRules) OR in_array("## end seo-rules (".$this->installation->getPackageID().") | Do not remove this line and the lines above ##", $ownSEORewriterRules))
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Gets the own Rewriter Rules
	 * 
	 * @access	protected
	 * 
	 * @return	void
	 */
	protected function getOwnRewriterRules()
	{
		return explode("\n", StringUtil::unifyNewlines(SEO_CUSTOM_REWRITE_RULES));
	}
	
	/**
	 * @access	protected
	 * 
	 * @return	void
	 */
	protected function addRulesToCustomRules($ownRewriterRules, $newRewriterRules)
	{
		$ownRewriterRules = implode("\n", $ownRewriterRules);
		$newRewriterRules = implode("\n", $newRewriterRules);		
		$return = $ownRewriterRules."\n\n## start seo-rules (".$this->installation->getPackageID().") | Do not remove this and the following lines ##\n".$newRewriterRules."\n## end seo-rules (".$this->installation->getPackageID().") | Do not remove this line and the lines above ##";
		
		return $return;
	}
	
	/**
	 * Saves the new SEO Rewriter Rules in Database
	 * 
	 * @access	protected
	 * 
	 * @param	string		$rewriterRules
	 * 
	 * @return	void
	 */
	protected function saveNewSEORewriterRules($rewriterRules)
	{
		// SQL-Instructions
		$sql = "UPDATE
						wcf".WCF_N."_option
				SET
						optionValue = '".$rewriterRules."'
				WHERE
						optionName = 'seo_custom_rewrite_rules'";
		
		// Build query
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * @see	 PackageInstallationPlugin::hasUpdate()
	 */
	public function hasUpdate()
	{
       parent::hasUpdate();
	}
	
	/**
	 * @see	 PackageInstallationPlugin::update()
	 */
	public function update() 
	{
		parent::update();
		
		// Uninstall
		$this->uninstall();
		
		// Install
		$this->install();
	}
	
	/**
	 * @see	 PackageInstallationPlugin::hasUninstall()
	 */
	public function hasUninstall()
	{
		return true;
	}
	
	/**
	 * @see PackageInstallationPlugin::uninstall()
	 */
	public function uninstall()
	{
		// Check Options
		if(!defined("SEO_CUSTOM_REWRITE_RULES"))
		{
			throw new SystemException("Option (SEO_CUSTOM_REWRITE_RULES) does not exists. This option is required for a successfully uninstallation!");
		}
		
		// Check packageID
		if($this->installation->getPackageID())
		{
			// Get SEO-Option
			$customRewriterRules = StringUtil::unifyNewlines(SEO_CUSTOM_REWRITE_RULES);
			
			// Remove from option
			$customRewriterRules = preg_replace("~## start seo-rules \(".$this->installation->getPackageID()."\) \| Do not remove this and the following lines ##(.*)## end seo-rules \(".$this->installation->getPackageID()."\) \| Do not remove this line and the lines above ##~es", "\n", $customRewriterRules);
			
			// Sql-instructions
			$sql = "UPDATE
						wcf".WCF_N."_option
					SET
						optionValue = '".$customRewriterRules."'
					WHERE
						optionName = 'seo_custom_rewrite_rules'";
			
			// Remove old config items
			WCF::getDB()->sendQuery($sql);
			
			// Build new .htaccess-file
			if(defined("WBB_DIR"))
			{
				// Load wbb-seo-util
				require_once(WBB_DIR.'lib/data/page/seo/WBBSEOUtil.class.php');
	
				// Create new File
				WBBSEOUtil::rebuildConfigFile();
			}
		}
	}
}

?>