<template>
    <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">Tags</div>
              <div class="card-body">
                <template v-for="(tag,index) in tags">
                  <button type="button" @click="searchByTags(tag.id)"
                          :class="tagIds.includes(tag.id)?'btn text-success bg-light':'btn bg-light text-dark'">
                    {{tag.name}}
                  </button>
                </template>
              </div>
            </div>
          </div>
          <div class="col-md-8" v-if="links.length > 0">
            <div class="card">
              <div class="card-header">Links</div>
              <ul class="list-group" v-for="(link,index) in links">
                <li class="list-group-item">
                  <p> Link: <a :href="link.href">{{ link.title}}</a></p>
                  <p>
                    Validity :
                    <template v-if="link.valid">
                      <svg style="display: inline;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle text-success" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                      </svg>
                    </template>
                    <template v-else>
                      <svg style="display: inline;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle text-danger" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                      </svg>
                    </template>
                  </p>
                  <p> Comments: {{ link.comments}} </p>
                  <p>Tags
                    <template v-for="(tag,index) in link.tags">
                      <button type="button"
                              :class="tagIds.includes(tag.id)?'btn text-success bg-light':'btn bg-light text-dark'">
                        {{tag.name}}
                      </button>
                    </template>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      data: () => ({
        tags: [],
        tagIds: [],
        links: [],
      }),
      mounted() {
        this.getTags()
      },
      methods: {
        getTags: function () {
          fetch('/api/tags')
              .then(response => response.json())
              .then((data) => {
                this.tags = data;
              })
              .catch(() => {})
        },
        searchByTags: function (tagId) {
          if (this.tagIds.includes(tagId)) {
            const index = this.tagIds.indexOf(tagId);
            if (index > -1) { // only splice array when item is found
              this.tagIds.splice(index, 1); // go to index and remove one item
            }
          } else {
            this.tagIds.push(tagId);
          }
          if (this.tagIds.length > 0) {
            fetch('/api/tags/' + this.tagIds.join(','))
                .then(response => response.json())
                .then((data) => {
                  this.links = data;
                })
                .catch(() => {})
          } else {
            this.links = [];
          }
        },
      }
    }
</script>
