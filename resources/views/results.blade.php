<x-app-layout>
    @include('components.sidebar')
    <div class="wrapper">
        @if(count($results) < 1)
            <div class="alert alert-primary" role="alert">
                Please fill the question to get result.
            </div>
        @else
        <section class="">
        <table class="table table-fixed">
        <thead>
        <tr>
                <th>ID</th>
                <th>Excess</th>
                <th>Balance</th>
                <th>Insufficiency</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
  </thead>
  <tbody>
            <?php foreach($results as $key => $result){ ?>
              
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo $result->excess; ?></td>
                <td><?php echo $result->balance; ?></td>
                <td><?php echo $result->insufficiency; ?></td>
                <td><?php echo $result->created_at; ?></td>
                <td>
                    <a disabled="false" aria-label="View" dusk="19-view-button"
                        class="toolbar-button hover:text-primary-500 px-2 disabled:opacity-50 disabled:pointer-events-none v-popper--has-tooltip"
                        href="<?php echo route('question.result.show',$result->id); ?>" > 
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            width="24" height="24" class="inline-block" role="presentation">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </a>
                    

                     
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        </section>
        @endif
    </div>
</x-app-layout>
