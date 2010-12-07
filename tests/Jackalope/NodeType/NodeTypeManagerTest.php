<?php

namespace Jackalope\NodeType;

use Jackalope\TestCase;

class NodeTypeManagerTest extends TestCase
{
    protected $ntm;

    public function setUp()
    {
        $this->ntm = $this->getNodeTypeManager();
    }

    /**
     * @covers \Jackalope\NodeType\NodeTypeManager::getNodeType
     */
    public function testGetNodeType()
    {
        $nt = $this->ntm->getNodeType('nt:file');
        $this->assertType('jackalope\NodeType\NodeType', $nt);
        $this->assertSame('nt:file', $nt->getName());
        $this->assertFalse($nt->isAbstract());
        $this->assertFalse($nt->isMixin());
        $this->assertFalse($nt->hasOrderableChildNodes());
        $this->assertTrue($nt->isQueryable());
        $this->assertSame('jcr:content', $nt->getPrimaryItemName());
    }

    /**
     * @covers \Jackalope\NodeType\NodeTypeManager::getDeclaredSubtypes
     * @covers \Jackalope\NodeType\NodeTypeManager::getSubtypes
     */
    public function testTypeHierarchies()
    {
        $nt = $this->ntm->getNodeType('nt:file');
        $this->assertSame(array('nt:hierarchyNode'), $nt->getDeclaredSupertypeNames());
        $this->assertSame(array(), $this->ntm->getDeclaredSubtypes('nt:file'));
        $this->assertSame(array(), $this->ntm->getSubtypes('nt:file'));
        $this->assertSame(array('nt:file', 'nt:folder', 'nt:linkedFile', 'rep:Authorizable', 'rep:AuthorizableFolder'), $this->ntm->getDeclaredSubtypes('nt:hierarchyNode'));
        $this->assertSame(array('nt:file', 'nt:folder', 'nt:linkedFile', 'rep:Authorizable', 'rep:Group', 'rep:User', 'rep:AuthorizableFolder'), $this->ntm->getSubtypes('nt:hierarchyNode'));
    }

    /**
     * @covers \Jackalope\NodeType\NodeTypeManager::hasNodeType
     */
    public function testHasNodeType()
    {
        $this->assertTrue($this->ntm->hasNodeType('nt:folder'), 'manager claimed to not know about nt:folder');
        $this->assertFalse($this->ntm->hasNodeType('nonode'), 'manager claimed to know about nonode');
    }

    public function testCountTypeClasses()
    {
        $allNodes = $this->ntm->getAllNodeTypes();
        $this->assertType('Iterator', $allNodes);
        $this->assertSame(52, count($allNodes));
        $this->assertType('jackalope\NodeType\NodeType', $allNodes->current());
        $primaryNodes = $this->ntm->getPrimaryNodeTypes();
        $this->assertType('Iterator', $primaryNodes);
        $this->assertSame(36, count($primaryNodes));
        $this->assertType('jackalope\NodeType\NodeType', $primaryNodes->current());
        $mixinNodes = $this->ntm->getMixinNodeTypes();
        $this->assertType('Iterator', $mixinNodes);
        $this->assertSame(16, count($mixinNodes));
        $this->assertType('jackalope\NodeType\NodeType', $mixinNodes->current());
    }


}
