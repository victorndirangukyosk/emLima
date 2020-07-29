# Git Workflow & Branching Model

It should be noted that our main production branch is named **production** and not the *master* branch.

The *production* branch at origin should be familiar to every contributor. We consider *origin/production* to be the main branch where the source code of HEAD always reflects a production-ready state.

Parallel to the *production* branch, another branch exists called *develop*.
We consider *origin/develop* to be the main branch where the source code of HEAD always reflects a state with the latest delivered development changes for the next release. Commits can be made directly on the branch or on separate branches
and directly merged onto the develop branch. A Continuous Deployment pipeline
has been built to deploy changes on this branch to the development server at https://dev.kwikbasket.com

When the source code in the develop branch reaches a stable point and is ready to be released, all of the changes should be merged through a Pull Request into the branch *staging* where another Continuous Delivery Pipeline exists to deploy changes to the staging server at https://stage.kwikbasket.com. After successful reviews, the production ready code is merged into the production branch through another pull request and then tagged with a release number which is the date in the format 'ddMMyyyy' e.g. 28072020