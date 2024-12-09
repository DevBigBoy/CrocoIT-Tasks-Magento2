<?php
declare(strict_types=1);
namespace Croco\Job\Block\Job;
class ListJob extends \Magento\Framework\View\Element\Template
{
// $_job: Stores an instance of the Job model, which is used to retrieve job data from the database.
    protected $_job;

//    $_department: Stores an instance of the Department model, representing the department associated with each job.
    protected $_department;

//    $_resource: Stores an instance of \Magento\Framework\App\ResourceConnection, used to interact with the database.
    protected $_resource;
//$_jobCollection: Caches the job collection for this block. It’s initialized to null and will be populated with job data the first time _getJobCollection() is called.
    protected $_jobCollection = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Croco\Job\Model\Job $job
     * @param \Croco\Job\Model\Department $department
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Croco\Job\Model\Job $job,
        \Croco\Job\Model\Department $department,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->_job = $job;
        $this->_department = $department;
        $this->_resource = $resource;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();


        // You can put these informations editable on BO
        //        getTitle()->set($title): Sets the page’s <title>.
        $title = __('We are hiring');
        // SetDescription($description): Sets the meta description.
        $description = __('Look at the jobs we have got for you');
        //    setKeywords($keywords): Sets the meta keywords.
        $keywords = __('job,hiring');

        $this->getLayout()->createBlock('Magento\Catalog\Block\Breadcrumbs');

        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'jobs',
                [
                    'label' => $title,
                    'title' => $title,
                    'link' => false // No link for the last element
                ]
            );
        }

        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->setDescription($description);
        $this->pageConfig->setKeywords($keywords);


        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }

        return $this;
    }

    protected function _getJobCollection()
    {
        if ($this->_jobCollection === null) {

            $jobCollection = $this->_job->getCollection()->addStatusFilter($this->_job, $this->_department);

            $this->_jobCollection = $jobCollection;
        }
        return $this->_jobCollection;
    }


    public function getLoadedJobCollection()
    {
        return $this->_getJobCollection();
    }

    public function getJobUrl($job){
        if(!$job->getId()){
            return '#';
        }

        return $this->getUrl('jobs/job/view', ['id' => $job->getId()]);
    }

    public function getDepartmentUrl($job){
        if(!$job->getDepartmentId()){
            return '#';
        }

        return $this->getUrl('jobs/department/view', ['id' => $job->getDepartmentId()]);
    }
}
